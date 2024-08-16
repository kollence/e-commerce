<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Image;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $order = 1;
        return [
            'filename' => fake()->unique()->word . '.jpg',
            'url' => fake()->imageUrl(640, 480),
            'sort_order' => $order++,
        ];
    }
}
