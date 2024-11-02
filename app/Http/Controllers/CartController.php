<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $uniqueKey = $request->input('cart_item_key');

        if (isset($this->items[$uniqueKey])) {
            // Remove the item from the cart
            unset($this->items[$uniqueKey]);
            // Update the session with the new cart
            session(['cart' => json_encode($this->items, JSON_UNESCAPED_UNICODE)]);

            return redirect()->back()->with('message', 'Item removed from the cart');
        }
        return redirect()->back()->with('message', 'Item not found in the cart');
    }

    /**
     * Generate a unique key for a cart item.
     * 
     * @param int $productItemId
     * @param string $sizeOptionName
     * @return string
     */
    private function generateUniqueKey($productItemId, $sizeOptionName)
    {
        // Convert sizeOptionName to uppercase and replace spaces with underscores
        $formattedSizeOptionName = strtoupper(str_replace(' ', '_', $sizeOptionName));
        // Return the unique key
        return $productItemId . '_' . $formattedSizeOptionName;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->forget();
        $taxRate = config('cart.tax') * 100;
        $cartSubtotal = $this->totalSubtotal();
        $tax = config('cart.tax');
        $cartTax = ($cartSubtotal * $tax);
        $cartTax =  round($cartTax, 2);
        $newTotal = round($cartSubtotal + $cartTax, 2);
        return inertia('Cart/Index', [
            'cart_items' => $this->items,
            'cart_subtotal' => $cartSubtotal, // Total price of all cart items
            'cart_tax' => $cartTax, // Total tax of all cart items
            'tax_rate' => $taxRate,
            'new_total' => $newTotal // Total price of all cart items including tax  
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (count($this->items) > 1) {
            unset($this->items[$id]);
        } else {
            $this->forget();
        }

        return $this->store();
    }

    public function forget()
    {
        $this->items = [];
        session()->forget('cart');
    }

    private function totalSubtotal()
    {
        return array_sum(array_column($this->items, 'subtotal'));
    }
}
