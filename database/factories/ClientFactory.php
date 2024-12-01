<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name, // Fake name
            'number' => $this->faker->phoneNumber, // Fake phone number
            'department' => fake()->randomElement(['Cashier\'s Office', 'Registrar\'s Office', 'Management Information System', 'Budget Office', 'College of Computing Studies', 'Business Affairs Office', 'Student Affairs & Services Office', 'College of Education', 'College of Arts and Sciences']), // Randomly selects a department
           
        ];
    }
}
