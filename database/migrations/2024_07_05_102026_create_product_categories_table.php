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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_category_id')->nullable(); // Nullable for root categories
            $table->unsignedBigInteger('size_category_id')->nullable(); // Nullable if not applicable

            $table->foreign('parent_category_id')->references('id')->on('product_categories')->onDelete('set null') ;
            $table->foreign('size_category_id')->references('id')->on('size_categories')->onDelete('set null'); // Adjust onDelete behavior as needed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
