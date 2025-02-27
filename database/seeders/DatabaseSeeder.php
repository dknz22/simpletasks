<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void {
        Role::firstOrCreate(['name' => 'Программист']);
        Role::firstOrCreate(['name' => 'Менеджер']);

        Employee::factory(20)->create();
        Task::factory(200)->create();
    }
}
