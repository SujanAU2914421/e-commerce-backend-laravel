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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Foreign key for orders table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key for products table
            $table->unsignedInteger('quantity'); // Quantity of the product
            $table->decimal('price', 10, 2); // Price per item
            $table->decimal('total_price', 10, 2); // Total price for this item (quantity * price)
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
