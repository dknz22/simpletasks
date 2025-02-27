<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating Employee model instances.
 *
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
            'name' => $this->faker->name(), // Generate a random name for the employee
            'email' => $this->faker->unique()->safeEmail(), // Generate a unique and safe email address
            'status' => $this->faker->randomElement(['active', 'on_leave']), // Assign a random status (either 'active' or 'on_leave')
        ];
    }

    /**
     * Configure the factory.
     *
     * This method ensures that after an employee is created, they are assigned
     * one or two random roles from the available roles in the database.
     */
    public function configure()
    {
        return $this->afterCreating(function (Employee $employee) {
            $roles = Role::all();
            $assignedRoles = $roles->random(rand(1, 2)); // Randomly assign either one or two roles to the employee
            $employee->roles()->attach($assignedRoles); // Attach the selected roles to the employee
        });
    }
}
