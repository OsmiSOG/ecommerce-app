<?php

namespace App\Models\Service;

use App\Models\Commerce\Category;
use App\Models\Commerce\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'limit',
        'active',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Get the user that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the subcategory that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /**
     * Get all of the servicePlans for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicePlans(): HasMany
    {
        return $this->hasMany(ServicePlan::class, 'service_id');
    }
}
