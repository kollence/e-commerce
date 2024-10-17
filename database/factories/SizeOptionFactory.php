<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SizeCategory;
use App\Models\SizeOption;

class SizeOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SizeOption::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $order = 1;
        $name = fake()->words(2, true);
        $slug = Str::slug($name);
        return [
            'name' => $name,
            'slug' => $slug,
            'size_description' => fake()->word(),
            'sort_order' => $order++,
            'size_category_id' => SizeCategory::factory(),
        ];
    }
}
