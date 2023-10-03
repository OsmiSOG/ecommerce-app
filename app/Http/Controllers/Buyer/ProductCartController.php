<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Commerce\Cart;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductCartController extends Controller
{
    function index() : \Inertia\Response
    {
        return Inertia::render('Commerce/Cart/CartOverview', [

        ]);
    }

    public function add(Request $request, Product $product) : RedirectResponse
    {
        $product->loadCount('stockAvailable');
        if (!$product->active || $product->sotck_available_count) {
            abort(402, 'Product not available or without stock');
        }

        $request->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['required', 'string']
        ]);

        $cart = Cart::current(auth()->user())->first();
        if (!$cart) {
            $cart = new Cart([
                'expired_at' => Carbon::now()->addDay(),
                'user_id' => auth()->user()->id
            ]);
            $cart->save();
        }

        $productCart = $cart->products()->wherePivot('product_id', $product->id)->first();

        if ($productCart) {
            if ($productCart->pivot->product_qty < $product->limit) {
                $cart->products()->updateExistingPivot($product->id, [
                    'product_qty' => $productCart->pivot->product_qty + 1
                ]);
            }
        } else {
            $cart->products()->attach($product->id, [
                'product_qty' => 1,
                'option_values' => null !== $request->get('options', []) ? json_encode($request->get('options', [])) : '[]'
            ]);
        }

        return redirect()->back();
    }
}
