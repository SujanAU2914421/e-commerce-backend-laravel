<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'status',
        'payment_status',
        'currency',
        'shipping_cost',

        // Order address fields
        'order_email',
        'order_first_name',
        'order_last_name',
        'order_street_address',
        'order_house_number_and_street_name',
        'order_apartment_details',
        'order_city',
        'order_state',
        'order_zip',
        'order_phone',
        'order_notes',

        // Billing address fields
        'billing_email',
        'billing_first_name',
        'billing_last_name',
        'billing_street_address',
        'billing_house_number_and_street_name',
        'billing_apartment_details',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_phone',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shipping_cost' => 'float',
    ];

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items associated with the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
