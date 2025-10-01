<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'country' => $this->faker->firstName(),
        'state' => $this->faker->firstName(),
        'city' => $this->faker->firstName(),
        'street' => $this->faker->firstName(),           
        ];
    }
}
