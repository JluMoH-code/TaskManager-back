<?php

namespace Database\Factories;

use App\Enums\PriorityTaskEnum;
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
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'active' => $this->faker->boolean(),
            'deadline' => $this->faker->dateTimeInInterval("now", "+5 days"),
            'priority' => $this->faker->randomElement(PriorityTaskEnum::values()),
        ];
    }
}
