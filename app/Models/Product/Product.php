<?php

namespace App\Models\Product;

use App\Models\Commerce\Category;
use App\Models\Commerce\Picture;
use App\Models\Commerce\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'type',
        'reference',
        'price',
        'description',
        'summary',
        'limit',
        'active',
        'in_stock',
    ];

    protected $casts = [
        'active' => 'bool',
        'in_stock' => 'bool',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the subcategory that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /**
     * Get all of the post's pictures.
     */
    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'picturable');
    }

    /**
     * Get all of the post's pictures.
     */
    public function frontPicture(): MorphOne
    {
        return $this->morphOne(Picture::class, 'picturable')->ofMany('created_at', 'min');
    }

    /**
     * Get all of the features for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_id');
    }

    /**
     * Get all of the options for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }

    /**
     * Get all of the deals for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals(): HasMany
    {
        return $this->hasMany(ProductDeal::class, 'product_id');
    }

    /**
     * Get all of the stock for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stock(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    /**
     * Get all of the stock for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockAvailable(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id')->whereNull('sold_at');
    }

    /**
     * Get all of the stock for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockSold(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id')->whereNotNull('sold_at');
    }

}
