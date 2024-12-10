<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\SizeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
        ->count(10)
        ->create()
        ->each(function ($product, $indexPr){
            $categories = Category::all();
            $repeat1to5 = $categories[$indexPr % $categories->count()];

            $product->update([
                'is_featured' => fake()->boolean,
            ]);

            $product->categories()->attach($repeat1to5);
            // Create and attach one image to the product
            $nameOverPicture = $product->slug . (string) $indexPr;
            $product->images()->create([
                'filename' => 'product-' . $nameOverPicture,
                'url' => fake()->imageUrl(800, 600, $nameOverPicture)
            ]);

            if ($product->categories->count() > 0) {

                $sizeOptions = SizeOption::where('size_category_id', $repeat1to5->size_category_id)->get();
            } else {
                // Handle case where there are no product categories
                logger()->error("Product {$product->id} has no product categories");
            }

            ProductItem::factory()
                ->count(3)
                ->for($product)
                ->create()
                ->each(function ($productItem, $index) use ($sizeOptions, $indexPr) { // attached 3 random size options to it
                    // $indexPr to string
                    $selectedSizeOptions = $sizeOptions->random(3);
                    $pivotData = [];
                    foreach ($selectedSizeOptions as $sizeOption) {
                        $pivotData[$sizeOption->id] = [
                            'sku' => 'SKU-' . strtoupper(str()->random(5)), // Example SKU
                            'in_stock' => rand(0, 100), // Random stock value
                        ];
                    }
                    $productItem->sizeOptions()->attach($pivotData);

                    // $uniqueImg = (string) $indexPr . (string) $index;
                    // Create and attach 3 images to the ProductItem
                    foreach (range(1, 3) as $i) {
                        $uniqueImg = $productItem->id . '-' . $i; // Create a unique identifier for each image
                        $productItem->images()->create([
                            'filename' => 'product-item-' . $uniqueImg,
                            'url' => fake()->imageUrl(800, 600, $uniqueImg),
                        ]);
                    }
                });
        });
    }
}
