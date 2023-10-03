<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Relations\HasOne;
use OsmiSOG\Subscriptions\Models\Plan as OsmisogPlan;

class Plan extends OsmisogPlan
{
    /**
     * Get the servicePlan associated with the Plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function servicePlan(): HasOne
    {
        return $this->hasOne(ServicePlan::class, 'plan_id');
    }
}
