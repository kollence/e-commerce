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

    public function add(Request $request)
    {
        // Extract the product_item_id, size_option, and quantity from the request
        $productItemId = $request->input('product_item_id');
        $sizeOption = $request->input('size_option');
        $quantity = $request->input('quantity', 1); // Default quantity to 1 if not provided

        // Check if the item already exists in the cart
        $existingItemIndex = $this->findExistingItemIndex($productItemId, $sizeOption['name']);

        if ($existingItemIndex !== null) {
            // Item exists, so increment the quantity
            $this->items[$existingItemIndex]['quantity'] += $quantity;
        } else {
            // Item doesn't exist, so add it as a new item
            $this->items[] = [
                'product_item_id' => $productItemId,
                'size_option' => $sizeOption,
                'quantity' => $quantity,
            ];
        }
        return $this->store();
    }

    /**
     * Find the index of an existing cart item by product_item_id and size_option name.
     * 
     * @param  int    $productItemId
     * @param  string $sizeOptionName
     * @return int|null
     */
    private function findExistingItemIndex($productItemId, $sizeOptionName)
    {
        foreach ($this->items as $index => $item) {
            if ($item['product_item_id'] == $productItemId && $item['size_option']['name'] == $sizeOptionName) {
                return $index;
            }
        }

        return null;
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
