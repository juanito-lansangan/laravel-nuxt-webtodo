<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => TaskPriority::Low,
            'due_date' => now()->addDay(5),
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'completed_at' => now(),
        ]);
    }

    public function archived(): static
    {
        return $this->state([
            'archived_at' => now(),
        ]);
    }
}
