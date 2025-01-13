<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Foreign key for user
            $table->string('status')->default('pending'); // Order status (e.g., pending, completed, canceled)
            $table->string('payment_method'); // Payment method (e.g., card, PayPal)
            $table->string('transaction_id')->nullable(); // Payment transaction ID (optional)
            $table->decimal('total_amount', 10, 2); // Total order amount
            $table->decimal('shipping_cost', 8, 2)->default(0); // Shipping cost
            $table->string('currency', 3)->default('USD'); // Currency code (e.g., USD, EUR)
            $table->string('shipping_address'); // Full shipping address
            $table->string('billing_address')->nullable(); // Full billing address (optional)
            $table->string('tracking_number')->nullable(); // Tracking number for shipments
            $table->timestamp('shipped_at')->nullable(); // Shipment date
            $table->timestamp('delivered_at')->nullable(); // Delivery date
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
