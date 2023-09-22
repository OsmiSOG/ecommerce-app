<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Commerce\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductFeature;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index() : \Inertia\Response
    {
        return Inertia::render('Seller/Products/Index');
    }

    public function get(Request $request) {
        return QueryBuilder::for(Product::class)
            ->paginate()
            ->appends($request->query());
    }

    public function create() : \Inertia\Response
    {
        return Inertia::render('Seller/Products/Form', [
            'categories' => Category::with('subcategories')->get(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:190'],
            'brand' => ['required', 'string', 'min:1', 'max:190'],
            'model' => ['required', 'string', 'min:1', 'max:190'],
            'type' => ['nullable', 'string', 'min:1', 'max:190'],
            'reference' => ['required', 'string', 'min:1', 'max:190'],
            'price' => ['required', 'numeric', 'gt:1'],
            'description' => ['required', 'string', 'min:1'],
            'summary' => ['required', 'string', 'min:1', 'max:100'],
            'limit' => ['nullable', 'integer'],
            'options' => ['nullable', 'array'],
            'options.*.name' => ['required', 'string'],
            'options.*.values' => ['required', 'array', 'min:1'],
            'options.*.values.*' => ['required', 'string', 'min:1', 'max:50'],
            'features' => ['nullable', 'array'],
            'features.*.name' => ['nullable', 'array'],
            'features.*.value' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $product = new Product($request->except(['options', 'features']));
            $product->save();

            if (isset($request->options) && !is_null($request->options)) {
                foreach ($request->options as $option) {
                    $productOption = new ProductOption($option);
                    $product->options()->save($productOption);
                    foreach ($option['values'] as $optionValue) {
                        $productOption->values()->save(new ProductOptionValue(['value' => $optionValue]));
                    }
                }
            }

            if (isset($request->features) && !is_null($request->features)) {
                foreach ($request->features as $feature) {
                    $product->features()->save(new ProductFeature($feature));
                }
            }

            DB::commit();
            return redirect()->route('sell.products.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function edit(Product $product) : \Inertia\Response
    {
        return Inertia::render('Seller/Products/Form', [
            'product' => $product
        ]);
    }

    public function update() {

    }
}
