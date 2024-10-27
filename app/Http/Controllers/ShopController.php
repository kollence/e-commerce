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
        $sort = request()->input('sort'); // Default to 'newest'
        $categories = Category::whereNull('parent_category_id')->with('allChildren')->get();
        $breadcrumbs = [];
        $queryParams = collect();
        if (request()->category) {
            // Fetch the requested category
            $category = Category::where('slug', request()->category)->with('allParents')->firstOrFail();
            if (!$category) {
                return redirect()->back()->with('message', 'Category not found.');
            }
        
            $breadcrumbs = $this->getBreadcrumbs($category);
            $selectedCategory = $category->name;
            $queryParams->put('category', $category->slug);
            // Get all the IDs of the requested category and its children
            $categoryIds = $category->children->pluck('id')->toArray();
            $categoryIds[] = $category->id; // Include the requested category itself
    
            // Fetch products that belong to any of these categories
            $query = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->with(['productItem.images', 'productItem.color']);
        } else {
            $selectedCategory = 'All';
            $query = Product::with(['productItem.images', 'productItem.color']);
        }
        $products = $query->get();
        // Apply sorting
        if(request()->sort){
            $queryParams->put('sort', $sort);
            switch ($sort) {
                case 'is_featured':
                    $products = $products->where('is_featured', true);
                    break;
                case 'price_ascending':
                    $products = $products->sortBy('productItem.price');
                    break;
                case 'price_descending':
                    $products = $products->sortByDesc('productItem.price');
                    break;
                case 'newest':
                    $products = $products->sortByDesc('productItem.created_at');
                    break;
                case 'oldest':
                    $products = $products->sortBy('productItem.created_at');
                    break;
            }
        }
        // dd($queryParams);
    
        return inertia('Shop/Index', [
            'products' => $products->values()->all(), // Ensure the collection is re-indexed,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'queryParams' => $queryParams, // Pass the query parameters to the view
            'breadcrumbs' => $breadcrumbs,
            'sort' => $sort,
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
    public function show(Product $product, ProductItem $productItem)
    {
        // Ensure the ProductItem belongs to the Product
        if ($product->id !== $productItem->product_id) {
            abort(404);
        }

        $productItem->load('color', 'sizeOptions', 'images');
        // Filter product items excluding the current productItem
        $productItems = $product->productItems()
            ->whereNot('id', $productItem->id)
            ->with(['color', 'sizeOptions', 'images'])
            ->get();

        return inertia('Shop/Show', [
            'product' => $product->load('brand'),
            'productItems' => $productItems,
            'productItem' => $productItem,       // DODAJ JOS KATEGORIJA. NASTAVI ODAVDE JER SVE RADI, SAMO VIDI BAGOVE KADA IMA VISE KATEGORIJA
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
