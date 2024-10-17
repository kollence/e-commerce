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

        Schema::create('product_item_size_option', function (Blueprint $table) {
            $table->foreignId('product_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('size_option_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->integer('in_stock')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_item_size_option');
    }
};
