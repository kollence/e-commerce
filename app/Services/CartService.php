<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Session;
use App\Enums\CartTypeEnum;

class CartService
{
    protected $cart;

    public function __construct(CartTypeEnum $cartType = CartTypeEnum::DEFAULT_CART)
    {
        $this->cart = new Cart($cartType);
    }

    public function addItem($productItem, $quantity, $sizeOption, $price)
    {
        $this->cart->addItem($productItem, $quantity, $sizeOption, $price);
    }

    public function getCartItems()
    {
        return $this->cart->getCartItems();
    }

    public function getCartSummary()
    {
        $subtotal = array_reduce($this->cart->getCartItems(), function ($carry, $item) {
            $productItem = ProductItem::findOrFail($item['product_item']['product_item_id']);
            $price = $productItem->price;
            return $carry + ($price * $item['product_item']['quantity']);
        }, 0);

        $taxRate = config('cart.tax');
        $cartTax = $this->taxedTotal($subtotal, $taxRate);
        $newTotal = round($subtotal + $cartTax, 2);

        $couponCode = Session::get('coupon')['code'] ?? null;
        $discountWithCoupon = Session::get('coupon')['discount'] ?? 0;
        $subtotalWithCoupon = max(0, $subtotal - $discountWithCoupon);
        $taxedTotalWithCoupon = $this->taxedTotal($subtotalWithCoupon, $taxRate);
        $newTotalWithCoupon = round($subtotalWithCoupon + $taxedTotalWithCoupon, 2);

        $cart_subtotal = $couponCode ? $subtotalWithCoupon : $subtotal;
        $cart_tax = $couponCode ? $taxedTotalWithCoupon : $cartTax;
        $new_total = $couponCode ? $newTotalWithCoupon : $newTotal;

        return [
            'tax_rate' => $taxRate * 100, // To represent percentage
            'cart_subtotal' => $cart_subtotal,
            'cart_tax' => $cart_tax,
            'new_total' => $new_total,
            'coupon' => [
                'code' => $couponCode,
                'discount' => $discountWithCoupon,
            ],
        ];
    }

    public function buildCartData()
    {
        return $this->cart->getCartItems();
    }

    public function updateCartItems($cartItems)
    {
        foreach ($cartItems as $key => $item) {
            $productItem = ProductItem::findOrFail($item['product_item_id']);
            $price = $productItem->price;
            $this->cart->updateItem($key, $item['quantity'], $price);
        }

        $this->cart->updateCart();
    }

    public function removeCartItem($key)
    {
        $this->cart->removeCartItem($key);
    }

    public function clearCart()
    {
        $this->cart->clearCart();
    }

    private function taxedTotal($subtotal, $taxRate)
    {
        return round($subtotal * $taxRate, 2);
    }
}
