<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('categories', App\Http\Controllers\CategoryController::class);

Route::resource('brands', App\Http\Controllers\BrandController::class);

Route::resource('products', App\Http\Controllers\ProductController::class);

Route::resource('colors', App\Http\Controllers\ColorController::class);

Route::resource('size-categories', App\Http\Controllers\SizeCategoryController::class);

Route::resource('size-options', App\Http\Controllers\SizeOptionController::class);

Route::resource('orders', App\Http\Controllers\OrderController::class);

Route::resource('users', App\Http\Controllers\UserController::class);
