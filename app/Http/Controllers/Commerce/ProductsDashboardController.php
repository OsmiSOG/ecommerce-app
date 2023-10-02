<?php

namespace App\Http\Controllers\Commerce;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Commerce\Category;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductsDashboardController extends Controller
{
    public function __invoke(Request $request): \Inertia\Response
    {
        $categories = Category::with('subcategories')->where('type', CategoryType::Product->name)->get();

        $products = QueryBuilder::for(Product::has('stockAvailable'))
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('subcategory_id'),
                AllowedFilter::custom('search', new FilterMultipleFields, 'name,brand,model,type,reference')
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'price')
            ->with(['category', 'subcategory', 'user', 'frontPicture'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Commerce/Product/Products', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
