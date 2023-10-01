<?php

namespace App\Http\Controllers\Seller;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Invokables\FilterMultipleFields;
use App\Models\Commerce\Category;
use App\Models\Commerce\Picture;
use App\Models\Service\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use OsmiSOG\Subscriptions\Enums\CurrencyEnum;
use OsmiSOG\Subscriptions\Enums\DiscountTypeEnum;
use OsmiSOG\Subscriptions\Enums\FrequencyEnum;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceController extends Controller
{
    public function index(Request $request) : \Inertia\Response {
        $services = QueryBuilder::for(Service::where('user_id', $request->user()->id))
            ->allowedFilters(AllowedFilter::custom('search', new FilterMultipleFields, 'name,description,created_at'))
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'name', 'limit', 'active', 'category_id', 'subcategory_id')
            ->with(['category', 'subcategory'])
            ->withCount('servicePlans')
            ->paginate()
            ->appends($request->query());

        $currencyEnum = CurrencyEnum::cases();
        $frequencyEnum = FrequencyEnum::cases();
        $discountTypeEnum = DiscountTypeEnum::cases();
        return Inertia::render('Seller/Services/Index', [
            'services' => $services,
            'currencies' => $currencyEnum,
            'frequencies' => $frequencyEnum,
            'discountTypes' => $discountTypeEnum
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
            'pictures' => ['required', 'array', 'min:1'],
            'pictures.*' => ['required', 'image'],
        ]);

        $service = new Service($request->all());
        $service->category()->associate($request->category_id);
        $service->subcategory()->associate($request->subcategory_id);
        $service->user()->associate($request->user()->id);
        $service->save();

        foreach ($request->pictures as $picture) {
            $name = $picture->hashName();
            $path = $picture->storeAs("services/$service->user_id/$service->id", $name, 'public');
            $service->pictures()->save(new Picture(['path' => $path]));
        }

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
