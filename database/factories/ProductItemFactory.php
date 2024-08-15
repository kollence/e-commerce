<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductItem;

class ProductItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_code' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'original_price' => fake()->numberBetween(1000, 5000),
            'sale_price' => fake()->numberBetween(500, 2000),
            'product_id' => Product::factory(),
            'color_id' => Color::factory(),
        ];
    }
}
