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
        Schema::create('size_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sort_order');
            $table->unsignedBigInteger('size_category_id')->nullable();
            $table->foreign('size_category_id')->references('id')->on('size_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_options');
    }
};
