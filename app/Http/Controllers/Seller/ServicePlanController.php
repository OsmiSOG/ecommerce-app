<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Service\Service;
use App\Models\Service\ServicePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use OsmiSOG\Subscriptions\Enums\CurrencyEnum;
use OsmiSOG\Subscriptions\Enums\DiscountTypeEnum;
use OsmiSOG\Subscriptions\Enums\FrequencyEnum;
use OsmiSOG\Subscriptions\Models\Plan;

class ServicePlanController extends Controller
{
    public function get(Service $service) {
        return $service->servicePlans->load('plan');
    }

    public function store(Request $request, Service $service) : RedirectResponse {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric'],
            'currency' => ['required', Rule::in(array_column(CurrencyEnum::cases(), 'value'))],
            'trial_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'trial_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'invoice_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'invoice_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'grace_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'grace_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'discount_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'discount_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'discount_subscribers_limit' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'discount_type_amount' => ['sometimes', 'nullable', Rule::in(array_column(DiscountTypeEnum::cases(), 'value'))],
            'discount_amount' => ['sometimes', 'nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer', 'max:100000'],
            'active_subscribers_limit' => ['nullable', 'integer', 'max:100000'],
        ]);

        $plan = new Plan($request->all());
        $plan->trial_period = $request->trial_period ?? 0;
        $plan->invoice_period = $request->invoice_period ?? 0;
        $plan->grace_period = $request->grace_period ?? 0;
        $plan->discount_period = $request->discount_period ?? 0;
        $plan->trial_interval = $request->trial_interval ?? FrequencyEnum::Day->value;
        $plan->invoice_interval = $request->invoice_interval ?? FrequencyEnum::Day->value;
        $plan->grace_interval = $request->grace_interval ?? FrequencyEnum::Day->value;
        $plan->discount_interval = $request->discount_interval ?? FrequencyEnum::Day->value;
        $plan->save();

        $servicePlan = new ServicePlan();
        $servicePlan->service()->associate($service);
        $servicePlan->plan()->associate($plan);
        $servicePlan->save();

        return redirect()->back();
    }

    public function update(Request $request, ServicePlan $servicePlan) : RedirectResponse {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric'],
            'currency_type' => ['required', Rule::in(array_column(CurrencyEnum::cases(), 'value'))],
            'trial_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'trial_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'invoice_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'invoice_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'grace_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'grace_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'discount_period' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'discount_interval' => ['sometimes', 'nullable', Rule::in(array_column(FrequencyEnum::cases(), 'value'))],
            'discount_subscribers_limit' => ['sometimes', 'nullable', 'integer', 'max:100000'],
            'discount_type_amount' => ['sometimes', 'nullable', Rule::in(array_column(DiscountTypeEnum::cases(), 'value'))],
            'discount_amount' => ['sometimes', 'nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer', 'max:100000'],
            'active_subscribers_limit' => ['nullable', 'integer', 'max:100000'],
        ]);

        $plan = $servicePlan->plan;
        $plan->update($request->all());

        return redirect()->back();
    }

    public function toggleActive(ServicePlan $servicePlan) : RedirectResponse {
        $plan = $servicePlan->plan;
        $plan->active = !$plan->active;
        $plan->update();

        return redirect()->back();
    }

    public function destroy(ServicePlan $servicePlan) : RedirectResponse {
        $servicePlan->delete();

        return redirect()->back();
    }
}
