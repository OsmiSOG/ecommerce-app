<?php

namespace App\Http\Controllers\Commerce;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Commerce\Category;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServicesDashboardController extends Controller
{
    public function __invoke(Request $request): \Inertia\Response
    {
        $categories = Category::with('subcategories')->where('type', CategoryType::Service->name)->get();

        $servicesQuery = Service::withCount(['servicePlans' => function ($query) {
                $query->whereHas('plan', fn ($query) => $query->where('active', true));
            }])
            ->whereHas('servicePlans.plan', function ($query) {
                $query->where('active', true);
            });

        $services = QueryBuilder::for($servicesQuery)
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('subcategory_id'),
                AllowedFilter::custom('search', new FilterMultipleFields, 'name,description')
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->with(['category', 'subcategory', 'user', 'frontPicture'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Commerce/Service/Services', [
            'categories' => $categories,
            'services' => $services,
        ]);
    }
}
