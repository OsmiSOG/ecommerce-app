<?php

namespace App\Invokables\Subscription;

use App\Models\Information\SubscriberInformation;
use App\Models\Sale\Sale;
use App\Models\Service\Service;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use OsmiSOG\Payments\Checkout\PurcharsePayment;
use OsmiSOG\Payments\Enums\TransactionMethods;
use OsmiSOG\Payments\Enums\TransactionStatus;
use OsmiSOG\Payments\Transaction\Models\Transaction;
use OsmiSOG\Payments\Transaction\Transactioner;
use OsmiSOG\Subscriptions\Enums\DiscountTypeEnum;
use OsmiSOG\Subscriptions\Enums\FrequencyEnum;
use OsmiSOG\Subscriptions\Models\Subscription;
use OsmiSOG\Subscriptions\Services\Period;

class Recurrence
{
    public function __invoke()
    {
        $subscriptions = Subscription::where(function ($query) {
            $query->where('grace_ends_at', '>=', Carbon::today()->startOfDay())
                ->where('trial_ends_at', '<=', Carbon::today()->startOfDay())
                ->whereColumn('trial_ends_at', 'starts_at');
        })
        ->orWhere(function ($query) {
            $query->where('grace_ends_at', '>=', Carbon::today()->startOfDay())->where('ends_at', '<=', Carbon::today()->startOfDay());
        })->whereNotNull('canceled_at');


        foreach ($subscriptions->cursor() as $subscription) {
            $plan = $subscription->plan;

            $total = $plan->price;
            if ($subscription->discount_ends_at && $subscription->ends_at->lte($subscription->discount_ends_at)) {
                if ($plan->discount_type_amount === DiscountTypeEnum::Percentage->value) {
                    $total = $total * $plan->discount_amount;
                } else {
                    $total = $total - $plan->discount_amount;
                }
            }

            DB::beginTransaction();
            try {
                $subscriberInfo = SubscriberInformation::where('subscription_id', $subscription->id)->first();
                if (!$subscriberInfo) {
                    return;
                }
                $service = Service::whereHas('servicePlans', function ($query) use ($plan) {
                    $query->where('plan_id', $plan->id);
                })->first();

                $sale = new Sale([
                    'total' => $total
                ]);
                $sale->salable()->associate($subscription);
                $sale->buyer()->associate($subscriberInfo->user_id);
                $sale->seller()->associate($service->user_id);
                $sale->save();

                $transaction = new Transaction($total, $plan->description, $plan->currency, TransactionMethods::Card->value, $plan->id, $subscriberInfo->client_id);
                $resourceTransaction = Transactioner::init()->generate($transaction);
                $history = $resourceTransaction->addHistory($sale);
                $resourceTransaction = PurcharsePayment::init()->payTokenized($history->trazability_id, $subscriberInfo->card_id, $subscriberInfo->card_token, 1);
                $history = $resourceTransaction->addHistory($sale);

                if ($history->resolved_at && $history->status === TransactionStatus::Approved->value) {
                    if ($subscription->ended()) {
                        $subscription = $subscription->renew();
                    } else {
                        $gracePeriod = new Period(FrequencyEnum::from($plan->grace_interval), $plan->grace_period, $subscription->ends_at);
                        $subscription->grace_ends_at = $gracePeriod->getEndDate();
                        $subscription->update();
                    }
                }

            } catch (RequestException $exception) {
                DB::rollBack();
                // $this->onFail($subscription, $amount, $plan);
                // \Sentry\captureException($exception);
                // \Sentry\captureMessage(json_encode($exception->response->json()));
            } catch (\Throwable $th) {
                DB::rollBack();
                // $this->onFail($subscription, $amount, $plan);
                // \Sentry\captureException($th);
            }

        }
    }
}
