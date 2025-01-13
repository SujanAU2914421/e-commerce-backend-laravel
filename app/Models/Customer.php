<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'avatar',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'gender',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the wish list items for the user.
     */
    public function wishLists(): HasMany
    {
        return $this->hasMany(WishList::class);
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the orders placed by the customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
