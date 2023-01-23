<?php

namespace Database\Factories;

use App\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'carrier_id' => Carrier::inRandomOrder()->value('id'),
            'weight_gram' => fake()->randomNumber(),
            'description' => fake()->text()
        ];
    }
}
