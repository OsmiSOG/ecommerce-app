<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductOverviewController extends Controller
{
    public function __invoke(Product $product) : \Inertia\Response {
        abort_if(!$product->active, 404);
        return Inertia::render('Commerce/Product/ProductOverview', [
            'product' => $product->load('user', 'category', 'subcategory', 'pictures', 'features', 'options')->loadCount('stockAvailable')
        ]);
    }
}
