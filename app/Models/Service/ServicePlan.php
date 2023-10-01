<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OsmiSOG\Subscriptions\Models\Plan;

class ServicePlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function booted()
    {
        static::deleted(function ($servicePlan) {
            $servicePlan->plan()->delete();
        });
    }

    /**
     * Get the service that owns the ServicePlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get the plan that owns the ServicePlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
