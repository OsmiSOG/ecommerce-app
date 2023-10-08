<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Product\Product;
use App\Models\Product\ProductStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductStockController extends Controller
{
    public function index($available = true, Product $product = null) {
        $stock = null;
        $page = $available ? 'Seller/Products/Stock/Available' : 'Seller/Products/Stock/Sold';

        if (!$product) {
            $stock = ProductStock::whereHas('product', function ($query) {
                $query->where('user_id', auth()->user()->id);
            });
            $stock = $available ? $stock->whereNull('sold_at') : $stock->whereNotNull('sold_at');
        } else {
            abort_if($product->user_id !== auth()->user()->id, 404);
            $stock = $available ? $product->stockAvailable() : $product->stockSold();
        }

        $stock = QueryBuilder::for($stock)
            ->allowedFilters(AllowedFilter::custom('search', new FilterMultipleFields, 'serial,sold_price'))
            ->defaultSort('-created_at')
            ->allowedSorts('serial', 'sold_price', 'automatic', 'sale_id', 'sold_at', 'created_at', 'product_id')
            ->with(['product', 'sale' => ['deliveryInformation', 'buyer']])
            ->paginate();

        return Inertia::render($page, [
            'product' => $product,
            'stock' => $stock
        ]);
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
        abort_if($productStock->product->user_id !== auth()->user()->id, 404);

        $productStock->delete();

        return redirect()->back();
    }
}
