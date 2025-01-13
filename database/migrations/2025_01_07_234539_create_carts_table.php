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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unique(['customer_id', 'product_id']); // Prevent duplicate cart entries

            $table->string("color");
            $table->string("size");

            $table->integer('quantity');


            // Optionally store price and discount when the product is added to the cart
            $table->decimal('price_at_time_of_addition', 8, 2); // Store price at the time of adding to cart
            $table->decimal('discount_at_time_of_addition', 8, 2)->default(0); // Store discount if any at the time of adding

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
