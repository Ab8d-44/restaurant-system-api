<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'prep_time' => $this->faker->time(),
            'is_vegetarian' => $this->faker->boolean(),
            'percentage' => $this->faker->numberBetween(10, 100),
            'description' => $this->faker->sentence(),
            'availability' => 'available',
            'category_id' => null,
        ];
    }
}
