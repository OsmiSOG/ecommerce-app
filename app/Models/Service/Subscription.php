<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OsmiSOG\Subscriptions\Models\Subscription as ModelsSubscription;

class Subscription extends ModelsSubscription
{
    /**
     * Get the plan that owns the Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
