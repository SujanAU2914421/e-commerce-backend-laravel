<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'customer_id', // The user who posted the comment
        'product_id', // The product being commented on
        'comment', // The comment content
        'rating', // Optional rating
    ];

    /**
     * Get the user that owns the comment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class); // Each comment belongs to a user
    }

    /**
     * Get the product that the comment is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class); // Each comment belongs to a product
    }
}
