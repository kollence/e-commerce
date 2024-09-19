<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\SizeOption;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_item_id' => ProductItem::factory(),
            'order_id' => Order::factory(),
            'quantity' => fake()->numberBetween(1, 12),
            'color_id' => Color::factory(),
            'size_option_id' => SizeOption::factory(),
            'product_code' => fake()->word(),
            'original_price' => fake()->numberBetween(12, 10000),
            'sale_price' => fake()->numberBetween(12, 10000),
            'total' => fake()->numberBetween(12, 10000),
        ];
    }
}
