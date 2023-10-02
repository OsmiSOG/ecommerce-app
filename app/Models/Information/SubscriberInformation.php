<?php

namespace App\Models\Information;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriberInformation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'number_id',
        'client_id',
        'card_id',
        'card_token',
        'card_label',
        'card_franchise',
        'user_id',
        'subscription_id'
    ];

    /**
     * Get the user that owns the SubscriberInformation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
