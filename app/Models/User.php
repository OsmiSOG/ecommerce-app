<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Commerce\Cart;
use App\Models\Information\CommerceInformation;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OsmiSOG\Subscriptions\Traits\HasSubscriptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the commerceInformation associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commerceInformation(): HasOne
    {
        return $this->hasOne(CommerceInformation::class, 'user_id');
    }

    /**
     * Get all of the carts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * Get the currentCart associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentCart(): HasOne
    {
        return $this->hasOne(Cart::class, 'user_id')->ofMany([
            'id' => 'max'
        ], function (Builder $query) {
            $query->whereNull('sold_at')->where('expired_at', '>', Carbon::now());
        });
    }
}
