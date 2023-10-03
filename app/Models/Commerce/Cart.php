<?php

namespace App\Models\Commerce;

use App\Models\Product\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sold_at',
        'expired_at',
        'user_id'
    ];

    protected $casts = [
        'sold_at' => 'datetime:Y-m-d H:i:s',
        'expired_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeCurrent($query, User $user) {
        $query->where('expired_at', '>', Carbon::now())
            ->where('user_id', $user->id)
            ->whereNull('sold_at');
    }

    /**
     * The products that belong to the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_cart', 'cart_id', 'product_id')->withPivot(['product_qty', 'option_values']);
    }
}
