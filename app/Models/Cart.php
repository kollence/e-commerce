<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;
use App\Enums\CartTypeEnum;

class Cart
{
    protected $items = [];
    protected $cartType;

    public function __construct(CartTypeEnum $cartType = CartTypeEnum::DEFAULT_CART)
    {
        $this->cartType = $cartType;
        $this->items = $this->cartItems();
    }

    /**
     * Add item to cart storing data in session (update or create)
     */
    public function addItem($productItem, $quantity, $sizeOption, $price)
    {
        $key = $this->generateUniqueKey($productItem->id, $sizeOption->slug);

        if (isset($this->items[$key])) {
            $this->items[$key]['product_item']['quantity'] += $quantity;
            $this->items[$key]['subtotal'] += $price * $quantity;
        } else {
            $this->items[$key] = [
                'product_item' => [
                    'product_item_id' => $productItem->id,
                    'quantity' => $quantity,
                    'size_option' => [
                        'id' => $sizeOption->id,
                        'slug' => $sizeOption->slug,
                    ],
                ],
                'subtotal' => $price * $quantity,
            ];
        }

        $this->updateCart();
    }

    /**
     * Get product item from cart
    */ 
    public function getCartItems()
    {
        $cartItems = $this->items;
        $detailedCartItems = [];

        foreach ($cartItems as $key => $item) {
            $productItem = ProductItem::with(['product', 'images', 'color', 'sizeOptions'])
                                     ->find($item['product_item']['product_item_id']);
            $sizeOptionFields = $productItem->sizeOptions->where('id', $item['product_item']['size_option']['id'])->first();
            $detailedCartItems[$key] = [
                'product' => [
                    'id' => $productItem->product->id,
                    'name' => $productItem->product->name,
                    'slug' => $productItem->product->slug,
                ],
                'product_item' => [
                    'product_item_id' => $productItem->id,
                    'product_code' => $productItem->product_code,
                    'original_price' => $productItem->original_price,
                    'sale_price' => $productItem->sale_price,
                    'images' => $productItem->images->map(function($image) {
                        return [
                            'id' => $image->id,
                            'filename' => $image->filename,
                            'url' => $image->url,
                            'is_active' => $image->is_active,
                            'sort_order' => $image->sort_order,
                            'main' => $image->main,
                        ];
                    })->toArray(),
                    'color' => [
                        'id' => $productItem->color->id,
                        'name' => $productItem->color->name,
                        'slug' => $productItem->color->slug,
                        'hex' => $productItem->color->hex,
                    ],
                    'quantity' => $item['product_item']['quantity'],
                    'size_option' => [
                        'id' => $item['product_item']['size_option']['id'],
                        'name' => $sizeOptionFields->name,
                        'slug' => $sizeOptionFields->slug,
                    ],
                    'sku' => $sizeOptionFields->pivot->sku,
                ],
                'subtotal' => $item['subtotal'],
            ];
        }

        return $detailedCartItems;
    }

    /**
     * Get cart items from session
     */
    public function cartItems()
    {
        $cart = json_decode(Session::get($this->cartType->value), true);
        return $cart['cart_items'] ?? [];
    }
    /** 
    * Update item in cart 
    */ 
    public function updateItem($key, $quantity, $price) 
    { 
        if (isset($this->items[$key])) { 
            $this->items[$key]['product_item']['quantity'] = $quantity; 
            $this->items[$key]['subtotal'] = $price * $quantity;
        } 
    }

    /**
     * Count items in cart
     */
    public function countCartItems()
    {
        return count($this->items);
    }

    /**
     * Get total of subtotal fields of cart items
     */
    public function totalSubtotal()
    {
        return array_sum(array_column($this->items, 'subtotal'));
    }
    /**
     * Update cart data in session
     */
    public function updateCart()
    {
        Session::put($this->cartType->value, json_encode([
            'cart_items' => $this->items,
        ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * Generate a unique key for a cart item.
     * 
     * @param int $productItemId
     * @param string $sizeOptionSlug
     * @return string
     */
    private function generateUniqueKey($productItemId, $sizeOptionSlug)
    {
        // Return the unique key to present ProductItem _ SizeOption 
        return $productItemId . '_' . strtoupper($sizeOptionSlug);
    }

    /**
     * Remove an item from the cart
     */
    public function removeCartItem($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
            $this->updateCart();
        }
    }

    /**
     * Clear all items from the cart and the session
     */
    public function clearCart()
    {
        $this->items = [];
        Session::forget($this->cartType->value);
    }
}
