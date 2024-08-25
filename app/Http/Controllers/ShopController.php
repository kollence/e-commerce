<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('parent_category_id', null)->with('children')->get();
        
        if(request()->category){
        // Fetch the requested category
        $category = Category::where('slug', request()->category)->firstOrFail();
        $selectedCategory = $category->name;
        // Get all the IDs of the requested category and its children
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id; // Include the requested category itself

        // Fetch products that belong to any of these categories
        $products = Product::whereHas('categories', function($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
        ->with('images')
        ->get();

        } else {
            $selectedCategory = 'All';
            $products = Product::with('images')->get();
        }
        return inertia('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
