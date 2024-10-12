<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('parent_category_id', null)->with('children')->get();
        $breadcrumbs = [];
        if (request()->category) {
            // Fetch the requested category
            $category = Category::where('slug', request()->category)->with('allParents')->firstOrFail();
            if (!$category) {
                return redirect()->back()->with('message', 'Category not found.');
            }
        
            $breadcrumbs = $this->getBreadcrumbs($category);
            $selectedCategory = $category->name;
            // Get all the IDs of the requested category and its children
            $categoryIds = $category->children->pluck('id')->toArray();
            $categoryIds[] = $category->id; // Include the requested category itself

            // Fetch products that belong to any of these categories
            $products = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->with(['productItem.images', 'productItem.color'])
                ->get();
        } else {
            $selectedCategory = 'All';
            $products = Product::with(['productItem.images', 'productItem.color'])->get();
        }
        // dd($products);
        return inertia('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    
    private function getBreadcrumbs($category)
    {
        $breadcrumbs = [];
        while ($category) {
            $breadcrumbs[] = [
                'name' => $category->name,
                'url' => route('shop.index', "category=". $category->slug),
            ];
            $category = $category->parent;
        }
    
        return array_reverse($breadcrumbs);
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
    public function show(Product $product, $color)
    {
        $productItem = $product->productItem()
        ->whereHas('color', function ($query) use ($color) {
            $query->where('slug', $color);
        })
        ->with(['color', 'sizeOptions', 'images'])
        ->firstOrFail(); // Load related data for the specific ProductItem
        return inertia('Shop/Show', [
            'product' => $product->load([
                'productItems' => function ($query) use ($productItem) {
                    $query->whereNot('id', $productItem->id)
                        ->with(['color', 'sizeOptions', 'images']); // Load related data for the specific ProductItem
                },
                'brand',
            ]),
            'productItem' => $productItem,        // DODAJ JOS KATEGORIJA NASTAVI ODAVDE JER SVE RADI SAMO VIDI BAGOVE KADA PRIPADA U VISE KATEGORIJA
            'breadcrumbs' => $this->getBreadcrumbs($product->categories->first()),
        ]);
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
