<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductStockController extends Controller
{
    public function get(Product $product, $available=true) {
        if ($available) {
            return $product->stockAvailable()->paginate();
        } else {
            return $product->stockSold()->paginate();
        }
    }

    public function store(Request $request, Product $product) : RedirectResponse {
        abort_if($product->user_id !== $request->user()->id, 404);

        $request->validate([
            'stock' => ['required', 'integer'],
            'serials' => ['nullable', 'array'],
            'serials.*' => ['required', 'string'],
            'automatic' => ['required', 'boolean'],
        ]);

        foreach ($request->serials as $serial) {
            $product->stock()->save(new ProductStock([
                'serial' => $serial,
                'automatic' => false,
            ]));
        }

        if ($request->automatic) {
            $stockAutomatic = $request->stock - count($request->serials);
            for ($i=0; $i < $stockAutomatic; $i++) {
                $product->stock()->save(new ProductStock([
                    'serial' => Str::random(8),
                    'automatic' => true,
                ]));
            }
        }

        return redirect()->back();
    }

    public function destroy(ProductStock $productStock) : RedirectResponse {
        abort_if(!is_null($productStock->sold_at), 422, 'The product has already been sold');

        $productStock->delete();

        return redirect()->back();
    }
}
