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
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // if customer is not assigned to a user
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('order_number')->unique()->nullable();
            $table->string('session_id')->unique()->nullable();
            // $table->enum('status', ["incomplete","processing","succeeded","cancelled"])->default('pending');
            $table->string('status');
            $table->integer('total_price');
            $table->integer('shipping_price');
            $table->string('shipping_method');
            $table->string('payment_method'); // STRIPE, PAYPAL, CRYPTO, COD
            $table->enum('payment_status', ["pending","paid","refunded", "failed","cancelled"])->default('pending');
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->string('currency')->default('USD');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
