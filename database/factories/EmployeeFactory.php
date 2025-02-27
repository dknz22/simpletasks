<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->randomElement(['active', 'on_leave']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Employee $employee) {
            $roles = Role::all();
            $assignedRoles = $roles->random(rand(1, 2));
            $employee->roles()->attach($assignedRoles);
        });
    }
}
