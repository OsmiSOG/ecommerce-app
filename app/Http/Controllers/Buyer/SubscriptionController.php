<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Information\SubscriberInformation;
use App\Models\Sale\Sale;
use App\Models\Service\Service;
use App\Models\Service\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use OsmiSOG\Payments\Checkout\PurcharsePayment;
use OsmiSOG\Payments\Client\ClientManager;
use OsmiSOG\Payments\Client\Models\Client;
use OsmiSOG\Payments\Enums\TransactionMethods;
use OsmiSOG\Payments\Tokenized\Models\Card;
use OsmiSOG\Payments\Tokenized\Tokenizer;
use OsmiSOG\Payments\Transaction\Models\Transaction;
use OsmiSOG\Payments\Transaction\Transactioner;
use OsmiSOG\Subscriptions\Enums\DiscountTypeEnum;
use OsmiSOG\Subscriptions\Models\Plan;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    public function index(Request $request) : \Inertia\Response {
        $subscriptions = QueryBuilder::for(Subscription::where('subscriber_id', $request->user()->id))
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'plan_id', 'trial_ends_at', 'starts_at', 'ends_at', 'grace_ends_at', 'discount_ends_at', 'canceled_at')
            ->with(['plan.servicePlan.service', 'subscriber'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Buyer/Subscriptions/Index', [
            'subscriptions' => $subscriptions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', Rule::exists('plans', 'id')
                ->whereNull('deleted_at')
                ->where('active', true)
            ],
            'card_holder' => ['required', 'string', 'max:80'],
            'card_number' => ['required'],
            'card_datetime' => ['required', 'date_format:m-y'],
            'card_cvv' => ['required'],
            'identification_type' => ['required', 'string'],
            'identification_number' => ['required', 'numeric'],
            'number_phone' => ['required', 'numeric'],
        ]);

        $planIds = Service::whereHas('servicePlans', function ($query) use ($request) {
            $query->where('plan_id', $request->plan_id);
        })->first()->servicePlans->pluck('plan_id');
        $subscription = $request->user()->subscriptions()->whereHas('plan', function ($query) use ($planIds) {
            $query->whereIn('plan_id', $planIds);
        })->first();
        if ($subscription) {
            abort(403, "You already have a subscription with this service or plan");
        }
        DB::beginTransaction();
        try {
            $plan = Plan::findOrFail($request->plan_id);
            $service = Service::whereHas('servicePlans', function ($query) use ($plan) {
                $query->where('plan_id', $plan->id);
            })->first();

            $subscriber = $request->user();

            $subscription = $subscriber->newPlanSubscription($plan);

            $tokenized = Tokenizer::init()->generate(new Card(
                $request->card_holder,
                $request->card_number,
                Carbon::parse($request->date)->year,
                Carbon::parse($request->date)->month,
                $request->card_cvv,
                1
            ));

            $subscriberInfo = SubscriberInformation::where('user_id', $request->user()->id)->where('number_id', $request->identification_number)->first();
            $client = null;
            if ($subscriberInfo) {
                $client = ClientManager::init()->get($request->identification_number);
            } else {
                $client = ClientManager::init()->create(new Client(
                    $request->user()->name,
                    $request->user()->email,
                    $request->identification_type,
                    $request->identification_number,
                    $request->number_phone,
                    'COL', 'City', 'Address'
                ));
                SubscriberInformation::create([
                    'number_id' => $request->identification_number,
                    'client_id' => $client->id(),
                    'card_id' => $tokenized->toArray()['id'],
                    'card_token' => $tokenized->getToken(),
                    'card_label' => $tokenized->toArray()['numberLabel'],
                    'card_franchise' => $tokenized->toArray()['franchise'],
                    'user_id' => $request->user()->id,
                    'subscription_id' => $subscription->id,
                ]);
            }

            if (!$subscription->onTrial()) {
                $total = $plan->price;
                if ($subscription->discount_ends_at && $subscription->ends_at->lte($subscription->discount_ends_at)) {
                    if ($plan->discount_type_amount === DiscountTypeEnum::Percentage->value) {
                        $total = $total * $plan->discount_amount;
                    } else {
                        $total = $total - $plan->discount_amount;
                    }
                }

                $sale = new Sale([
                    'total' => $total
                ]);
                $sale->salable()->associate($subscription);
                $sale->buyer()->associate($subscriber);
                $sale->seller()->associate($service->user_id);
                $sale->save();

                $transaction = new Transaction($total, $plan->description, $plan->currency, TransactionMethods::Card->value, $plan->id, $client->id());
                $resourceTransaction = Transactioner::init()->generate($transaction);
                $history = $resourceTransaction->addHistory($sale);
                $resourceTransaction = PurcharsePayment::init()->payTokenized($history->trazability_id, $tokenized->toArray()['id'], $tokenized->getToken(), 1);
                $resourceTransaction->addHistory($sale);
            }

            DB::commit();
            return redirect()->back();
        } catch (RequestException $exception) {
            DB::rollBack();
            // \Sentry\captureException($exception);
            // \Sentry\captureMessage(json_encode($exception->response->json()));
            throw $exception;

        } catch (\Throwable $th) {
            DB::rollBack();
            // \Sentry\captureException($th);
            throw $th;
        }

    }

    public function cancel(Subscription $subscription) : RedirectResponse {
        abort_if($subscription->subscriber_id !== auth()->user()->id, 404);

        $subscription->cancel();

        return redirect()->back();
    }
}
