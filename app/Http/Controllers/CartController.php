<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        // $this->items = [];
        // session()->forget('cart');
        dd($request->all());
        // $quantity = $this->getQuantity($product, $quantity);
    //     array:11 [▼ // app/Http/Controllers/CartController.php:18
    //     "id" => 5
    //     "name" => "qui aut"
    //     "size_option" => "38C"
    //     "sku" => "SKU-EVCDA"
    //     "product_code" => "ZZB1JWE4T4"
    //     "original_price" => 2736
    //     "sale_price" => 1062
    //     "images" => array:3 [▶]
    //     "in_stock" => 65
    //     "quantity" => 3
    //     "submitted_pxq" => 3186
    //   ]
        // $product = Product::find($request->id);
        // $uid = str_replace(' ', '-', strtolower($product->id . '_' . $request->size_option));
        // // $quantity = $this->getQuantity($product, $quantity);
        // if (isset($this->items[$uid])) {
            
        //     if(strcmp(serialize(['sale_price' => $request->sale_price, 'original_price' => $request->original_price]), serialize(['sale_price' => $product->lowestPricedItem->sale_price, 'original_price' => $product->lowestPricedItem->original_price])) === 0){
        //         $this->items[$uid]['quantity'] = (int) $request->quantity;
        //         $this->items[$uid]['in_stock'] = (int) $request->in_stock;
        //         $this->items[$uid]['submitted_pxq'] = (int) $request->submitted_pxq;
        //     }else{
        //         throw new Exception("Product out dated", 1);
        //     }

        // } else {
        //     $this->items[$uid] = $request->all();
        // }

        // return $this->store();
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
        //
    }
}
