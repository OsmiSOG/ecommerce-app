<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Commerce\Cart;
use App\Models\Product\DeliveryInformation;
use App\Models\Sale\Sale;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use OsmiSOG\Payments\Checkout\PurcharsePayment;
use OsmiSOG\Payments\Client\ClientManager;
use OsmiSOG\Payments\Client\Models\Client;
use OsmiSOG\Payments\Enums\TransactionCurrency;
use OsmiSOG\Payments\Enums\TransactionMethods;
use OsmiSOG\Payments\Tokenized\Models\Card;
use OsmiSOG\Payments\Transaction\Models\Transaction;
use OsmiSOG\Payments\Transaction\Transactioner;

class ProductCheckoutController extends Controller
{
    public function __invoke(Request $request) : RedirectResponse
    {
        $request->validate([
            'cart_id' => ['required', Rule::exists('carts', 'id')->where('user_id', $request->user()->id)->whereNull('sold_at')],
            'card_holder' => ['required', 'string', 'max:80'],
            'card_number' => ['required'],
            'card_datetime' => ['required', 'date_format:m-y'],
            'card_cvv' => ['required'],
            'card_installments' => ['required', 'integer'],
            'identification_type' => ['required', 'string'],
            'identification_number' => ['required', 'numeric'],
            'number_phone' => ['required', 'numeric'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'details' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $cart = Cart::where('id', $request->cart_id)->where('expired_at', '>', Carbon::now())->firstOrFail();

            $products = $cart->products;

            $sale = new Sale([
                'total' => $cart->total
            ]);
            $sale->salable()->associate($cart);
            $sale->buyer()->associate($request->user());
            $sale->save();

            DeliveryInformation::create(array_merge($request->all(), [
                'name' => $request->card_holder,
                'sale_id' => $sale->id,
            ]));

            foreach ($products as $product) {
                $productsStock = $product->stockAvailable()->limit($product->pivot->product_qty)->get();
                foreach ($productsStock as $stock) {
                    $stock->sold_price = $product->price;
                    $stock->sold_at = Carbon::now();
                    $stock->sale()->associate($sale);
                    $stock->update();
                }
            }
            $cart->sold_at = Carbon::now();
            $cart->update();

            $client = ClientManager::init()->get($request->identification_number);
            if (!$client) {
                $client = ClientManager::init()->create(new Client(
                    $request->user()->name,
                    $request->user()->email,
                    $request->identification_type,
                    $request->identification_number,
                    $request->number_phone,
                    $request->country, $request->city, $request->address
                ));
            }

            $transaction = new Transaction($cart->total, 'Cart purcharsed at: '.Carbon::now()->timestamp, TransactionCurrency::USD->value, TransactionMethods::Card->value, $cart->id, $client->id());
            $resourceTransaction = Transactioner::init()->generate($transaction);
            $history = $resourceTransaction->addHistory($sale);
            $resourceTransaction = PurcharsePayment::init()->pay($history->trazability_id, new Card(
                $request->card_holder,
                $request->card_number,
                Carbon::createFromFormat('m-y', $request->card_datetime)->year,
                Carbon::createFromFormat('m-y', $request->card_datetime)->month,
                $request->card_cvv,
                $request->card_installments,
            ));
            $resourceTransaction->addHistory($sale);

            DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
