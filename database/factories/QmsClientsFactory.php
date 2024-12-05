<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\QmsWindow;

class QmsClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomWindow = QmsWindow::inRandomOrder()->first();

        return [
            'gName' => $this->faker->firstName,
            'sName' => $this->faker->lastName,
            'studentNo' => $this->faker->numerify('###-####'),
            'dept_id' => $randomWindow->dept_id, // Use the dept_id from the selected QmsWindow
            'w_name' => $randomWindow->w_name,   // Use the w_name corresponding to the dept_id
        ];
        
    }
}
