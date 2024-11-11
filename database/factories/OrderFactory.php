<?php

namespace Database\Factories;

use App\Models\Address;
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
        $user = User::factory()->create(); 
        $billingAddress = Address::factory()->create(['user_id' => $user->id]); 
        $shippingAddress = Address::factory()->create(['user_id' => $user->id]);
        // Generating a unique hashed value with 5 to 6 characters length 
        $orderNumber = fake()->unique()->regexify('[A-Z0-9]{5,6}');
        return [
            'user_id' => User::factory(),
            'order_number' => $orderNumber,
            'status' => fake()->randomElement(['pending','processing','completed','cancelled']),
            'total_price' => fake()->numberBetween(10, 10000),
            'shipping_price' => fake()->numberBetween(10, 10000),
            'shipping_method' => fake()->word(),
            'payment_method' => fake()->word(),
            'payment_status' => fake()->randomElement(["pending","paid","refunded", "failed","cancelled"]),
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'currency' => fake()->currencyCode(),
            'notes' => fake()->sentence(),
        ];
    }
}
