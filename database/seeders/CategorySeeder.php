<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SizeCategory;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentCategories = [
            'Men' => Category::factory()->create(['name' => 'Men', 'slug' => 'men']),
            'Women' => Category::factory()->create(['name' => 'Women', 'slug' => 'women']),
            'Kids' => Category::factory()->create(['name' => 'Kids', 'slug' => 'kids']),
        ];

        Category::factory()
            ->count(5)
            ->create()
            ->each(function ($category, $index) use ($parentCategories) {
                $category->parent_category_id = rand(1, count($parentCategories));
                $category->update(['size_category_id' => fake()->randomElement(SizeCategory::pluck('id')->toArray())]);
            });
    }
}
