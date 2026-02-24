<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
    {
        return [
            'name' => fake()->company . ' Store',
            'location' => fake()->city . ', ' . fake()->state(),
            'operating_hours' => fake()->numberBetween(6, 10) . ':00 AM - ' . fake()->numberBetween(6, 11) . ':00 PM',
            'is_active' => fake()->boolean(95),
        ];
    }
}
