<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'store_name' => $this->faker->company . ' Store',
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'latitude' => 0,
            'longitude' => 0,
            'phone' => $this->faker->phoneNumber,
            'operating_hours' => '9:00 AM - 6:00 PM',
            'is_active' => true,
        ];
    }
}