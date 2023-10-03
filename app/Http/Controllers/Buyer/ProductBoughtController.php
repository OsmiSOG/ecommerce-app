<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Commerce\Cart;
use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class ProductBoughtController extends Controller
{
    public function index(Request $request) : \Inertia\Response {
        $soldCarts = Sale::where('buyer_id', $request->user()->id)->whereHasMorph('salable', Cart::class);

        $carts = QueryBuilder::for($soldCarts)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'total', 'seller_id')
            ->with(['salable.products.frontPicture'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Buyer/PurcharsedHistory/Index', [
            'carts' => $carts
        ]);
    }
}
