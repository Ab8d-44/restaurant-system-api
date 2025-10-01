<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                        'national_id' => $this->faker->unique()->numerify('##########'),
            'position' => $this->faker->randomElement(['Manager', 'Chef', 'Waiter']),
            'salary' => $this->faker->numberBetween(3000, 10000),
            'bonus' => $this->faker->numberBetween(0, 1000),
            'notes' => $this->faker->sentence(),
            'hire_date' => $this->faker->date(),
                        'user_id' => \App\Models\User::factory(),

        ];
    }
}
