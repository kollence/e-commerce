<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SizeCategory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->words(1, true);
        $slug = Str::slug($name);
        return [
            'name' => $name,
            'slug' => $slug,
            // 'is_active' => fake()->boolean(),
            'parent_category_id' => null,
            'size_category_id' => null,
        ];
    }
}
