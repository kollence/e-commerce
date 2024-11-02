<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductItem;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $items = [];
    public function __construct()
    {
        $this->items = session()->has('cart') ? json_decode(session('cart'), true) : [];
    }

    /**
     * Add a product item to the cart.
     */
    public function add(Request $request)
    {
        $productItemId = $request->input('product_item_id');
        $sizeOptionId = $request->input('size_option_id');
        $quantity = $request->input('quantity', 1); // Default quantity to 1 if not provided

        $productItem = ProductItem::findOrFail($productItemId);
        $sizeOption = $productItem->sizeOptions->where('id', $sizeOptionId)->firstOrFail();
        $price = $productItem->price; // Appended `price` field in `ProductItem`
        
        $cart = new Cart();
        $cart->addItem($productItem, $quantity, $sizeOption, $price);

        $message = "Added new item to cart";
        return redirect()->back()->with("message", $message);
    }

    public function updateQuantity(Request $request)
    {
        if($request->input('cart_items')){
            $cartItems = $request->input('cart_items');
            $cart = new Cart();
            $cart->updateCartItems($cartItems);
        } else {
            return redirect()->back()->with("message", "Item in cart doesn't exist");
        }
    }

    /**
     * Remove a product item from the cart.
     */
    public function remove(Request $request)
    {
        $key = $request->input('cart_item_key');
        $cart = new Cart();
        $cart->removeCartItem($key);
    
        return redirect()->back()->with('message', 'Item removed from the cart');
    }
    
    public function clear()
    {
        $cart = new Cart();
        $cart->clearCart();
    
        return redirect()->back()->with('message', 'Cart cleared');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = new Cart();
        $cartData = $cart->getCartItems();
        $orderSummary = $cart->getCartSummary();
        
        return inertia('Cart/Index', [
            'cart_items' => $cartData,  
            'order_summary' => $orderSummary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return session()->put('cart', json_encode($this->items, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    private function totalSubtotal()
    {
        return array_sum(array_column($this->items, 'subtotal'));
    }
}
