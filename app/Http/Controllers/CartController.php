<?php

namespace App\Http\Controllers;

use App\Models\ProductItem;
use Exception;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller { 

    protected $cartService; 
    
    public function __construct() 
    {
        $this->cartService = new CartService(); 
    } 

    public function add(Request $request) 
    { 
        $productItemId = $request->input('product_item_id'); 
        $sizeOptionId = $request->input('size_option_id'); 
        $quantity = $request->input('quantity', 1); 

        $productItem = ProductItem::findOrFail($productItemId); 
        $sizeOption = $productItem->sizeOptions->where('id', $sizeOptionId)->firstOrFail(); 
        $price = $productItem->price; 

        $this->cartService->addItem($productItem, $quantity, $sizeOption, $price);

        $message = "Added new item to cart";
        return redirect()->back()->with("message", $message);
    } 

    public function index() 
    { 
        $cartData = $this->cartService->getCartItems();
        $orderSummary = $this->cartService->getCartSummary();

        return inertia('Cart/Index', [
            'cart_items' => $cartData,  
            'order_summary' => $orderSummary,
        ]);
    } 

    public function update(Request $request) 
    {
        if($request->input('cart_items')){
            $cartItems = $request->input('cart_items');

            $this->cartService->updateCartItems($cartItems);
        } else {
            return redirect()->back()->with("message", "Item in cart doesn't exist");
        }
    } 

    public function remove(Request $request) 
    { 
        $key = $request->input('cart_item_key');

        if($key){
            $this->cartService->removeCartItem($key);

            return redirect()->back()->with('message', 'Item removed from the cart');
        }
        return redirect()->back()->with('message', 'Item not selected or doesn\'t exist');
    } 

    public function clear() 
    { 
        $this->cartService->clearCart();

        return redirect()->back()->with('message', 'Cart cleared');
    } 
}