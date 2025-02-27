<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Seeds the database with initial data for roles, employees, and tasks.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void {
        // Ensure that both roles exist in the database before seeding employees
        Role::firstOrCreate(['name' => 'Программист']);
        Role::firstOrCreate(['name' => 'Менеджер']);

        Employee::factory(20)->create(); // Create 20 employees with randomly assigned roles
        Task::factory(200)->create();
    }
}
