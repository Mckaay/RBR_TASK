<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Task>
 */
final class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = array_column(User::all('id')->toArray(), 'id');
        return [
            'name' => $this->faker->text(255),
            'user_id' => $this->faker->randomElement($userIds),
            'description' => $this->faker->text(255),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 years'),
            'status' => $this->faker->randomElement(
                Status::cases(),
            )->value,
            'priority' => $this->faker->randomElement(
                Priority::cases(),
            )->value,
        ];
    }
}
