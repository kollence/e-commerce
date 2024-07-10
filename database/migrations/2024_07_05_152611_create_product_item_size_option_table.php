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
        Schema::create('product_item_size_option', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_item_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('sku');
            $table->integer('qty_stock');

            $table->foreign('product_item_id')->references('id')->on('product_items')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('size_options')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_item_size_option', function (Blueprint $table) {
            $table->dropForeign(['product_item_id']);
            $table->dropForeign(['size_id']);
        });
        Schema::dropIfExists('product_variations');
    }
};
