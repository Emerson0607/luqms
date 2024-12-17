<?php

namespace Database\Factories;
use App\Models\QmsSharedWindow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QmsSharedClients>
 */
class QmsSharedClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomWindow = QmsSharedWindow::inRandomOrder()->first();

        return [
            'gName' => $this->faker->firstName,
            'sName' => $this->faker->lastName,
            'studentNo' => $this->faker->numerify('###-####'),
            'dept_id' => $randomWindow->dept_id, // Use the dept_id from the selected QmsWindow
            'w_name' => $randomWindow->w_name,   // Use the w_name corresponding to the dept_id
        ];
    }
}
