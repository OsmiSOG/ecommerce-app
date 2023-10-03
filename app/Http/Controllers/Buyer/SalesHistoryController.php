<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Commerce\Cart;
use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OsmiSOG\Subscriptions\Models\Subscription;
use Spatie\QueryBuilder\QueryBuilder;

class SalesHistoryController extends Controller
{
    public function index(Request $request) {

        $type = $request->get('type', '');
        $sales = Sale::where('buyer_id', $request->user()->id);

        if ($type == 'shopping') {
            $sales = $sales->whereHasMorph('salable', Cart::class);
        } else if ($type == 'subscription') {
            $sales = $sales->whereHasMorph('salable', Subscription::class);
        }

        $salesQuery = QueryBuilder::for($sales)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'total', 'seller_id', 'salable_type')
            ->with(['salable', 'transaction'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Buyer/Transactions/Index', [
            'sales' => $salesQuery
        ]);
    }
}
