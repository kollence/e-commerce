<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;

    protected $items = [];

    public function __construct()
    {
        $this->items = $this->cartItems();
    }

    /**
     * Add item to cart storing data in session (update or create)
     */
    public function addItem($productItem, $quantity, $sizeOption, $price)
    {
        // $key = "{$productItem->id}_{$sizeOption->slug}";
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
     * Get cart_items from session
    */
    public function cartItems()
    {
        $cart = json_decode(Session::get('default_cart'), true);
        return $cart['cart_items'] ?? [];
    }

    public function getCartSummary()
    {
        $subtotal = array_reduce($this->items, function ($carry, $item) {
            $productItem = ProductItem::findOrFail($item['product_item']['product_item_id']);
            // $price = $productItem->sale_price ?? $productItem->original_price;
            $price = $productItem->price;
            return $carry + ($price * $item['product_item']['quantity']);
        }, 0);
        
        $taxRate = config('cart.tax');
        $cartTax = $this->taxedTotal($subtotal);
        $newTotal = round($subtotal + $cartTax, 2);
        return [
            'cart_subtotal' => $subtotal,
            'cart_tax' => $cartTax,
            'tax_rate' => $taxRate * 100, // To represent percentage
            'new_total' => $newTotal
        ];
    }
    
    private function totalSubtotal()
    {
        return array_sum(array_column($this->items, 'subtotal'));
    }

    public function taxedTotal($subtotal)
    {
        $taxRate = config('cart.tax');
        return round($subtotal * $taxRate, 2);
    }

    /**
     * Get product item from cart
    */ 
    public function getCartItems()
    {
        $cartItems = $this->cartItems();
        $detailedCartItems = [];

        foreach ($cartItems as $key => $item) {
            $productItem = ProductItem::with(['product', 'images', 'color', 'sizeOptions'])
                                     ->find($item['product_item']['product_item_id']);
            
            $detailedCartItems[$key] = [
                'product' => [
                    'id' => $productItem->product->id,
                    'name' => $productItem->product->name,
                ],
                'product_item' => [
                    'product_item_id' => $productItem->id,
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
                        'name' => $productItem->sizeOptions->where('id', $item['product_item']['size_option']['id'])->first()->name,
                    ],
                ],
                'subtotal' => $item['subtotal'],
            ];
        }

        return $detailedCartItems;
    }

    protected function updateCart()
    {
        Session::put('default_cart', json_encode([
            'cart_items' => $this->items,
            'order_summary' => $this->getCartSummary()
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

    public function updateCartItems($cartItems)
    {
        foreach ($cartItems as $key => $item) {
                if (isset($this->items[$key])) {
                    $productItem = ProductItem::findOrFail($item['product_item_id']);
                    // $price = $productItem->sale_price ?? $productItem->original_price;
                    $price = $productItem->price;
                    $this->items[$key]['product_item']['quantity'] = $item['quantity'];
                    $this->items[$key]['subtotal'] = $price * $item['quantity'];
                
            }

            $this->updateCart();
        }
    }

    public function removeCartItem($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
            $this->updateCart();
        }
    }

    public function clearCart()
    {
        $this->items = [];
        Session::forget('default_cart');
    }
}
