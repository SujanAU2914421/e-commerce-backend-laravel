<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    // Indicating that timestamps are being used
    public $timestamps = true;

    // The attributes that are mass assignable
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'price_at_time_of_addition',
        'discount_at_time_of_addition',
    ];

    /**
     * Define the relationship between the cart and the user.
     * A cart belongs to a single user.
     */

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class); // One cart belongs to one user
    }

    /**
     * Define the relationship between the cart and the product.
     * A cart contains a single product.
     */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class); // One cart contains one product
    }
}
