<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductStock;
use App\Models\Sale\Sale;
use App\Models\Service\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OsmiSOG\Payments\Enums\TransactionStatus;
use OsmiSOG\Subscriptions\Models\Subscription as ModelsSubscription;

class DashboardController extends Controller
{
    public function index() {
        $products = ProductStock::whereIn('product_id', function ($query) {
            $query->select('id')->from('products')->where('user_id', auth()->user()->id);
        })->whereNotNull('sold_at')->get();

        $productsDetail = [
            'total' => $products->count(),
            'amount' => $products->sum('sold_price'),
        ];

        $totalSubscriptions = Subscription::whereHas('plan.servicePlan.service', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $amountSubscriptions = Sale::whereHasMorph('salable', ModelsSubscription::class)->whereHas('transaction', function ($query) {
            $query->where('status', TransactionStatus::Approved->value);
        })->sum('total');

        $subscriptionsDetail = [
            'total' => $totalSubscriptions,
            'amount' => $amountSubscriptions,
        ];

        return Inertia::render('Seller/Dashboard', [
            'productsDetail' => $productsDetail,
            'subscriptionsDetail' => $subscriptionsDetail
        ]);
    }
}
