<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
        // Predefined set of department codes and their full names
        $departments = [
            'IT' => 'Information Technology',
            'HR' => 'Human Resources',
            'MKTG' => 'Marketing',
            'FIN' => 'Finance',
            'SALES' => 'Sales',
            'ENG' => 'Engineering',
        ];

        // Get a random department code and its full name
        $department_code = array_rand($departments);
        $department_name = $departments[$department_code];

        return [
            'name' => $department_name,  // Full department name
            'dept_code' => $department_code, // Abbreviation (e.g., IT, HR)
        ];
    }
}
