<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Foreign key to customers table
            $table->enum('status', ['delivered', 'approved', 'pending', 'shipping', 'cancelled'])->default('pending'); // Status of the order
            $table->enum('payment_status', ['paid', 'unpaid', 'refunded'])->default('unpaid'); // Payment status of the order
            $table->string('currency')->default('$'); // Currency for the order
            $table->decimal('shipping_cost', 8, 2)->default(0); // Shipping cost

            // Order address fields
            $table->string('order_email')->nullable();
            $table->string('order_first_name')->nullable();
            $table->string('order_last_name')->nullable();
            $table->string('order_street_address')->nullable();
            $table->string('order_house_number_and_street_name')->nullable();
            $table->string('order_apartment_details')->nullable();
            $table->string('order_city')->nullable();
            $table->string('order_state')->nullable();
            $table->string('order_zip')->nullable();
            $table->string('order_phone')->nullable();
            $table->text('order_notes')->nullable();

            // Billing address fields
            $table->string('billing_email')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_street_address')->nullable();
            $table->string('billing_house_number_and_street_name')->nullable();
            $table->string('billing_apartment_details')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_phone')->nullable();

            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
