<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DmsDepartment;

class QmsClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gName' => $this->faker->firstName,
            'sName' => $this->faker->lastName,
            'studentNo' => fake()->numerify('###-####'),
            'dept_id' => DmsDepartment::inRandomOrder()->value('id'),
        ];
        
    }
}
