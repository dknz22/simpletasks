<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating Task model instances.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(), // Generate a random task title
            'description' => $this->faker->paragraph(), // Generate a random task description
            'status' => $this->faker->randomElement(['to_do', 'in_progress', 'done']), // Assign a random status from predefined options
        ];
    }
}
