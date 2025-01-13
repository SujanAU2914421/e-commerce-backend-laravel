<?php

namespace App\Models;

use App\Models\Columns\ProductColumns;
use App\Models\Schema\ProductSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use ProductSchema;
    use ProductColumns;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'currency',
        'discount',
        'price',
        'stock',
        'sizes',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'sizes' => 'array',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        // Automatically set the `user_id` to the currently authenticated user during save
        static::saving(function ($product) {
            if (Auth::check()) {
                $product->user_id = Auth::id();
            }
        });

        // Automatically load the user relationship after retrieving a product
        static::retrieved(function ($product) {
            $product->load('user');
        });
    }

    /**
     * Get the user that owns the product.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that this product belongs to.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the wishlists that include this product.
     *
     * @return HasMany
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    /**
     * Get the comments for the product.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(Color::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
