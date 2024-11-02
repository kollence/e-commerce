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
        // Return product item with size option that will be unique product item with its in_stock value
        $productItem = ProductItem::with(['sizeOptions' => function($query) use ($sizeOption) {
            $query->where('name', $sizeOption['name']); // Filter by size name
        }])
        ->where('id', $productItemId)
        ->first();

        $productItemPrice = ($productItem->sale_price < $productItem->original_price && $productItem->sale_price > 0) ? $productItem->sale_price : $productItem->original_price;
        // Generate a unique key for the cart item
        $uniqueKey = $this->generateUniqueKey($productItemId, $sizeOption['name']);

        if (isset($this->items[$uniqueKey])) {
            if($this->items[$uniqueKey]['product_item']['quantity'] < $productItem->sizeOptions->first()->pivot->in_stock){
                $this->items[$uniqueKey]['product_item']['quantity'] += $quantity;
                $this->items[$uniqueKey]['subtotal'] += ($productItemPrice * $quantity);
            }
            $message = "Updated item in cart";
        } else {
            // Item doesn't exist, so add it as a new item
            $this->items[$uniqueKey] = [
                'product' => [
                    'id' => $productItem->product->id,
                    'name' => $productItem->product->name,
                ],
                'product_item' => [
                    'product_item_id' => $productItemId,
                    'original_price' => $productItem->original_price,
                    'sale_price' => $productItem->sale_price,
                    'images' => $productItem->images,
                    'color' => $productItem->color,
                    'quantity' => $quantity,
                    'size_option' => $sizeOption,
                ],
                'subtotal' => $productItemPrice * $quantity, // Calculating subtotal
            ];
            $message = "Added new item to cart";
        }
        session()->put('cart', json_encode($this->items, JSON_UNESCAPED_UNICODE));
        return redirect()->back()->with("message", $message);
    }

    public function updateQuantity(Request $request)
    {
        if($request->input('cart_items')){
            $cartItems = $request->input('cart_items');
            foreach ($cartItems as  $productItemUniqueKey => $cartItem ) {
                $productItemPrice = ($cartItem['product_item']['sale_price'] < $cartItem['product_item']['original_price'] && $cartItem['product_item']['sale_price'] > 0) ? $cartItem['product_item']['sale_price'] : $cartItem['product_item']['original_price'];
                $this->items[$productItemUniqueKey]['product_item']['quantity'] = (int) $cartItem['product_item']['quantity'];
                $this->items[$productItemUniqueKey]['subtotal'] = $productItemPrice * $cartItem['product_item']['quantity'];
            }
            session()->put('cart', json_encode($this->items, JSON_UNESCAPED_UNICODE));
        }else{
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
