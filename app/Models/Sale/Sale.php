<?php

namespace App\Models\Sale;

use App\Models\Commerce\Cart;
use App\Models\Product\DeliveryInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OsmiSOG\Payments\Models\HistoryTransaction;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'total'
    ];

    protected $casts= [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = [
        'type_salable'
    ];

    public function typeSalable(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->salable_type == Cart::class) {
                    return 'Shopping';
                } else {
                    return 'Subscription';
                }
            },
        );
    }

    /**
     * Get the parent picturable model (product or service).
     */
    public function salable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the user that owns the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the post's image.
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(HistoryTransaction::class, 'paymentable');
    }

    /**
     * Get the deliveryInformation associated with the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryInformation(): HasOne
    {
        return $this->hasOne(DeliveryInformation::class, 'sale_id');
    }
}
