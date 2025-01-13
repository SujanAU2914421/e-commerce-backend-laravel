<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'payment_method',
        'transaction_id',
        'total_amount',
        'shipping_cost',
        'currency',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * Get the user that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
