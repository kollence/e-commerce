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

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_item_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->foreignId('color_id')->nullable()->constrained();
            $table->foreignId('size_option_id')->nullable()->constrained();
            $table->string('product_code');
            $table->integer('original_price');
            $table->integer('sale_price')->nullable();
            $table->integer('total');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
