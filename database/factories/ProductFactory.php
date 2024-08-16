<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        $slug = Str::slug($name);
        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->sentence(),
            'care_instructions' => fake()->sentence(),
            'about' => fake()->sentence(),
            // 'is_active' => fake()->boolean(true),
            // 'product_category_id' => ProductCategory::factory()->create()->id,
            'brand_id' =>  Brand::factory(),
        ];
    }
}
