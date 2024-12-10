<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'street_and_number' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->word(),
            'country' => fake()->country(),
            'zip_code' => fake()->postcode(),
            'phone_1' => fake()->phoneNumber(),
        ];
    }
}
