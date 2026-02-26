<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'product_name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'barcode' => fake()->unique()->ean13(),
            'image_url' => null,
            'is_active' => true,
        ];
    }
}