<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

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
            'product_id' => Product::factory(),
            'order_id' => Order::factory(),
            'quantity' => fake()->numberBetween(1, 12),
            'unit_amount' => fake()->numberBetween(1, 5),
            'total_amount' => fake()->numberBetween(1, 10),
            'price' => fake()->numberBetween(12, 10000),
            'total' => fake()->numberBetween(12, 10000),
        ];
    }
}
