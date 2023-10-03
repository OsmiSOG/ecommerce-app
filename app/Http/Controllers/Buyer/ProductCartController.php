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
        $cart = Cart::with('products.frontPicture')->current(auth()->user())->first();

        return Inertia::render('Commerce/Cart/CartOverview', [
            'cart' => $cart ? $cart->append('total') : null
        ]);
    }

    public function add(Request $request, Product $product) : RedirectResponse
    {
        $product->loadCount('stockAvailable');
        if (!$product->active || !$product->stock_available_count) {
            abort(401, 'Product not available or without stock');
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
            if ($productCart->pivot->product_qty < $product->limit && $productCart->pivot->product_qty < $product->stock_available_count) {
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

    public function substract(Product $product) : RedirectResponse
    {
        $cart = Cart::current(auth()->user())->first();

        if (!$cart) {
            return abort(404);
        }

        $productCart = $cart->products()->wherePivot('product_id', $product->id)->first();

        if ($productCart) {
            if ($productCart->pivot->product_qty === 1) {
                $cart->products()->detach($product->id);
            } else {
                $cart->products()->updateExistingPivot($product->id, [
                    'product_qty' => $productCart->pivot->product_qty - 1
                ]);
            }
        } else {
            return abort(404);
        }

        return redirect()->back();
    }

    public function remove(Product $product) : RedirectResponse
    {
        $cart = Cart::current(auth()->user())->first();

        if (!$cart) {
            return abort(404);
        }

        $productCart = $cart->products()->wherePivot('product_id', $product->id)->first();

        if ($productCart) {
            $cart->products()->detach($product->id);
        } else {
            return abort(404);
        }

        return redirect()->back();

    }
}
