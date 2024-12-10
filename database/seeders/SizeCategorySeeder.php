<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SizeCategory;
use App\Models\SizeOption;

class SizeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $sizeCategories = [
            'shirt sizes' => ['S', 'M', 'L', 'XL'],
            'display sizes' => ['5:4', '4:3', '3:2 (15:10)', '5:3 (15:9)'],
            'voltages' => ['600w', '700w', '720w', '800w'],
            'liters' => ['0.33L', '0.75L', '1L', '2L'],
            'cup sizes' => ['30F', '32E', '30DD', '38C'],
        ];

        collect($sizeCategories)->each(function ($options, $name) {
            $sizeCategory = SizeCategory::factory()->create(['name' => $name, 'slug' => str($name)->slug()]);
            $sortOrder = 1;
            foreach ($options as $value) {
                SizeOption::factory()
                    ->for($sizeCategory)
                    ->create(['name' => $value, 'slug' => str($value)->slug(), 'sort_order' => $sortOrder++]);
            }
        });
    }
}
