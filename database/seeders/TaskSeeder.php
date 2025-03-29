<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

final class TaskSeeder extends Seeder
{
    public function run(): void
    {

        $testingUser = User::where('email', 'test@example.com')->first('id');

        Task::factory()->count(10)->create([
            'user_id' => $testingUser->id,
        ]);
        Task::factory()->count(200)->create();
    }
}
