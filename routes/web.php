<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Welcome Routes
 */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
/**
 * Shop Routes
 */
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}/{productItem}', [ShopController::class, 'show'])->name('shop.show');
/**
 * Cart Routes
 */
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:40,1');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/canceled', [CheckoutController::class, 'canceled'])->name('checkout.canceled');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
/**
 * Coupon Routes
 */
Route::post('coupon', [CouponController::class, 'apply'])->name('coupon.apply');
Route::delete('coupon', [CouponController::class, 'forget'])->name('coupon.forget');
/**
 * Dashboard Routes
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
