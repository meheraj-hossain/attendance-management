<?php

namespace Database\Factories\Admin;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'          => fake()->unique()->numberBetween(1, 99),
            'employee_name'        => fake()->name(),
            'employee_email'       => fake()->email(),
            'employee_designation' => fake()->word(),
            'employee_department'  => fake()->word(),
            'employee_status'      => '1',
        ];
    }
}
