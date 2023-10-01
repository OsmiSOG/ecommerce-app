<?php

namespace App\Http\Controllers\Seller;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Models\Commerce\Category;
use App\Models\Service\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceController extends Controller
{
    public function index(Request $request) : \Inertia\Response {
        $services = QueryBuilder::for(Service::where('user_id', $request->user()->id))
            ->with(['category', 'subcategory'])
            ->paginate()
            ->appends($request->query());

        return Inertia::render('Seller/Services/Index', [
            'services' => $services
        ]);
    }

    public function create() : \Inertia\Response {
        return Inertia::render('Seller/Services/Form', [
            'categories' => Category::where('type', CategoryType::Service->name)->with('subcategories')->get(),
        ]);
    }

    public function store(Request $request) : RedirectResponse {
        $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')->where('type', CategoryType::Service->name)],
            'subcategory_id' => ['required', Rule::exists('subcategories', 'id')->where('category_id', $request->get('category_id'))],
            'name' => ['required', 'string', 'max:190'],
            'description' => ['required', 'string', 'max:500'],
            'limit' => ['nullable', 'integer'],
        ]);

        $service = new Service($request->all());
        $service->category()->associate($request->category_id);
        $service->subcategory()->associate($request->subcategory_id);
        $service->user()->associate($request->user()->id);
        $service->save();

        return redirect()->route('sell.service.index');
    }

    public function edit(Service $service) : \Inertia\Response {
        return Inertia::render('Seller/Services/Form', [
            'service' => $service,
            'categories' => Category::where('type', CategoryType::Service->name)->with('subcategories')->get(),
        ]);
    }

    public function update(Request $request, Service $service) : RedirectResponse {
        $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')->where('type', CategoryType::Service->name)],
            'subcategory_id' => ['required', Rule::exists('subcategories', 'id')->where('category_id', $request->get('category_id'))],
            'name' => ['request', 'string', 'max:190'],
            'description' => ['request', 'string', '500'],
            'limit' => ['nullable', 'integer'],
        ]);

        $service->category()->associate($request->category_id);
        $service->subcategory()->associate($request->subcategory_id);
        $service->update($request->all());

        return redirect()->route('sell.service.index');
    }

    function toggleActive(Service $service) : JsonResponse {
        $service->active = !$service->active;
        $service->update();

        return response()->json([
            'saved' => true
        ]);
    }
}
