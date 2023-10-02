<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Service\Service;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function __invoke(): Response
    {
        $products = Product::with('frontPicture')->has('stockAvailable')->orderBy('created_at')->limit(8)->get();
        $services = Service::with('frontPicture')
            ->withCount(['servicePlans' => function ($query) {
                $query->whereHas('plan', fn ($query) => $query->where('active', true));
            }])
            ->whereHas('servicePlans.plan', function ($query) {
                $query->where('active', true);
            })->orderBy('created_at')->limit(8)->get();

        return Inertia::render('Commerce/Home', [
            'products' => $products,
            'services' => $services,
        ]);
    }
}
