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
use Illuminate\Validation\Rule;
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
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'subcategory_id' => ['required', Rule::exists('subcategories', 'id')],
            'name' => ['required', 'string', 'min:1', 'max:190'],
            'brand' => ['required', 'string', 'min:1', 'max:190'],
            'model' => ['required', 'string', 'min:1', 'max:190'],
            'type' => ['nullable', 'string', 'min:1', 'max:190'],
            'reference' => ['required', 'string', 'min:1', 'max:190'],
            'price' => ['required', 'numeric', 'gt:1'],
            'description' => ['required', 'string', 'min:1'],
            'summary' => ['required', 'string', 'min:1', 'max:100'],
        ]);

        DB::beginTransaction();
        try {
            $product = new Product($request->all());
            $product->active = true;
            $product->in_stock = false;
            $product->category()->associate($request->category_id);
            $product->subcategory()->associate($request->subcategory_id);
            $product->user()->associate(auth()->user());
            $product->save();

            DB::commit();
            return redirect()->route('sell.product.index');
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
