<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\SizeCategory;
use App\Models\SizeOption;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => '123123123'
        ]);

        // $sizeCategoryNames = ['shirt sizes', 'display sizes', 'voltages', 'liters', 'cup sizes'];
        $sizeCategories = [
            'shirt sizes' => ['S', 'M', 'L', 'XL'],
            'display sizes' => ['5:4', '4:3', '3:2 (15:10)', '5:3 (15:9)'],
            'voltages' => ['600w', '700w', '720w', '800w'],
            'liters' => ['0.33L', '0.75L', '1L', '2L'],
            'cup sizes' => ['30F', '32E', '30DD', '38C'],
        ];

        // Create size categories with specific names and their size options
        $sizeCategoryIds = collect($sizeCategories)->map(function ($options, $name) {
            $sizeCategory = SizeCategory::factory()->create(['name' => $name, 'slug' => str($name)->slug()]);
            $sortOrder = 1;
            foreach ($options as $value) {

                SizeOption::factory()
                    ->for($sizeCategory)
                    ->create(['name' => $value, 'slug' => str($value)->slug(), 'sort_order' => $sortOrder++]);
            }

            return $sizeCategory->id;
        });

        $parentCategories = [
            'Men' => Category::factory()->create(['name' => 'Men', 'slug' => 'men']),
            'Women' => Category::factory()->create(['name' => 'Women', 'slug' => 'women']),
            'Kids' => Category::factory()->create(['name' => 'Kids', 'slug' => 'kids']),
        ];

        // create product categories and add them size_category_id
        $categories = Category::factory()
            ->count(5)
            ->create()
            ->each(function ($category, $index) use ($sizeCategoryIds, $parentCategories) {
                $category->parent_category_id = rand(1, count($parentCategories));
                $randomIndex = rand(1, $sizeCategoryIds->count());
                $category->update(['size_category_id' => $randomIndex]);
            });

        // create product with product items with attached size options to it
        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product, $indexPr) use ($categories) {

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
    
        
        
        $this->call([
            CouponSeeder::class,
            // UserSeeder::class,
            // SizeCategorySeeder::class,
            // CategorySeeder::class,
            // ProductSeeder::class,
        ]);
    }
}
