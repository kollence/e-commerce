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
            // optional i would like to have fewer sale prices,
            'sale_price' => fake()->optional($weight = 0.6, $default = null)->numberBetween(500, 2000),
            'product_id' => Product::factory(),
            'color_id' => Color::factory(),
        ];
    }
}
