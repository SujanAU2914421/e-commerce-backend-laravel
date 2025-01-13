<?php

use App\Models\Category;
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete(); // User ID
            $table->foreignIdFor(Category::class)->nullable()->constrained()->nullOnDelete(); // Category ID
            $table->string('title'); // Product name
            $table->text('description'); // Product description
            $table->string('currency'); // Product currency
            $table->decimal('discount', 10, 2); // Product discount
            $table->decimal('price', 10, 2); // Price with two decimal places
            $table->integer('stock')->unsigned(); // Stock quantity
            $table->json('sizes')->nullable(); // Sizes as a JSON field
            $table->softDeletes(); // Soft delete column
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
