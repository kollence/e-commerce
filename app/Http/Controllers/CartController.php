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
        // dd($request->all());
        $id = $request->input('id');
        $name = $request->input('name');
        $productItemId = $request->input('product_item_id');
        $sizeOption = $request->input('size_option');
        $quantity = $request->input('quantity', 1); // Default quantity to 1 if not provided
        $productItem = ProductItem::find($productItemId);
        // dd($productItem);

        // Generate a unique key for the cart item
        $uniqueKey = $this->generateUniqueKey($productItemId, $sizeOption['name']);

        if (isset($this->items[$uniqueKey])) {
            // Item exists, so increment the quantity
            $this->items[$uniqueKey]['quantity'] += $quantity;
        } else {
            // Item doesn't exist, so add it as a new item
            $this->items[$uniqueKey] = [
                'id' => $id,
                'name' => $name,
                'product_item_id' => $productItemId,
                'size_option' => $sizeOption,
                'quantity' => $quantity,
                'original_price' => $productItem->original_price,
                'sale_price' => $productItem->sale_price,
                'images' => $productItem->images,
                'color' => $productItem->color,
            ];
        }

        return $this->store();
        // return $this->forget();
        
    }

    /**
     * Remove a product item from the cart.
     */
    public function remove(Request $request)
    {
        $productItemId = $request->input('product_item_id');
        $sizeOption = $request->input('size_option');

        // Generate the unique key for the item to remove
        $uniqueKey = $this->generateUniqueKey($productItemId, $sizeOption['name']);

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
        return inertia('Cart/Index', [
            'items' => $this->items,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
}
