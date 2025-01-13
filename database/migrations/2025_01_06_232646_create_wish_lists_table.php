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
        Schema::create('wish_lists', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key to products table
            $table->unique(['customer_id', 'product_id']); // Prevent duplicate wishlist entries
            $table->timestamps(); // Adds 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wish_lists');
    }
};
