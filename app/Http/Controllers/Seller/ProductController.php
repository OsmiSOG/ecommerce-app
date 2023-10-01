<?php

namespace App\Http\Controllers\Seller;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Commerce\Category;
use App\Models\Commerce\Picture;
use App\Models\Product\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(Request $request) : \Inertia\Response
    {
        $products = QueryBuilder::for(Product::where('user_id', $request->user()->id))
            ->allowedFilters(AllowedFilter::custom('search', new FilterMultipleFields, 'name,brand,model,type,reference'))
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'name', 'brand', 'model', 'type', 'price', 'limit', 'active', 'in_stock', 'category_id', 'subcategory_id')
            ->with(['category', 'subcategory'])
            ->paginate()
            ->appends($request->query());

        // return dd($products);
        return Inertia::render('Seller/Products/Index', [
            'products' => $products
        ]);
    }

    public function get(Request $request) {
        return QueryBuilder::for(Product::class)
            ->paginate()
            ->appends($request->query());
    }

    public function create() : \Inertia\Response
    {
        return Inertia::render('Seller/Products/Form', [
            'categories' => Category::where('type', CategoryType::Product->name)->with('subcategories')->get(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')->where('type', CategoryType::Product->name)],
            'subcategory_id' => ['required', Rule::exists('subcategories', 'id')->where('category_id', $request->get('category_id'))],
            'name' => ['required', 'string', 'min:1', 'max:190'],
            'brand' => ['required', 'string', 'min:1', 'max:190'],
            'model' => ['required', 'string', 'min:1', 'max:190'],
            'type' => ['nullable', 'string', 'min:1', 'max:190'],
            'reference' => ['required', 'string', 'min:1', 'max:190'],
            'price' => ['required', 'numeric', 'gt:1'],
            'description' => ['required', 'string', 'min:1'],
            'summary' => ['required', 'string', 'min:1', 'max:100'],
            'pictures' => ['required', 'array', 'min:1'],
            'pictures.*' => ['required', 'image'],
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

            foreach ($request->pictures as $picture) {
                $name = $picture->hashName();
                $path = $picture->storeAs("products/$product->user_id/$product->id", $name, 'public');
                $product->pictures()->save(new Picture(['path' => $path]));
            }

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

    public function update(Request $request, Product $product) : RedirectResponse
    {
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

    public function toggleActive(Product $product) : RedirectResponse
    {
        $product->active = !$product->active;
        $product->update();

        return redirect()->back();
    }
}
