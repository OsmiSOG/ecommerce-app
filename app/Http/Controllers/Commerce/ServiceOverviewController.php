<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceOverviewController extends Controller
{
    public function __invoke(Service $service) : \Inertia\Response {
        abort_if(!$service->active, 404);
        return Inertia::render('Commerce/Service/ServiceOverview', [
            'service' => $service->load(['user', 'category', 'subcategory', 'pictures', 'servicePlans.plan' => function ($query) {
                $query->where('active', true);
            }])
        ]);
    }
}
