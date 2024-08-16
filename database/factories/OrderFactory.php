<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending','processing','completed','cancelled']),
            'total_price' => fake()->numberBetween(10, 10000),
            'shipping_price' => fake()->numberBetween(10, 10000),
            'shipping_method' => fake()->word(),
            'payment_method' => fake()->word(),
            'payment_status' => fake()->randomElement(["pending","paid","refunded", "failed","cancelled"]),
            'billing_address' => fake()->streetAddress(),
            'shipping_address' => fake()->streetAddress(),
            'currency' => fake()->currencyCode(),
            'notes' => fake()->sentence(),
        ];
    }
}
