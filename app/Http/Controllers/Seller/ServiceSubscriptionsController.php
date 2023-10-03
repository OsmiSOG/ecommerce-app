<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Service\Service;
use App\Models\Service\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceSubscriptionsController extends Controller
{
    public function subscribers(Request $request, Service $service) : \Inertia\Response {
        abort_if($service->user_id !== $request->user()->id, 404);

        $subscriptions = Subscription::whereHas('plan', function ($query) use ($service) {
            $query->whereIn('id', $service->servicePlans->pluck('plan_id'));
        });

        $subscriptionsQuery = QueryBuilder::for($subscriptions)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'plan_id', 'subscriber_id', 'trial_ends_at', 'ends_at', 'created_at')
            ->with(['plan', 'subscriber'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Seller/Services/Subscribers', [
            'subscriptions' => $subscriptionsQuery,
            'service' => $service
        ]);
    }
}
