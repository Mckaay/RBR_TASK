<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskShareToken;
use Illuminate\Support\Str;

final class TaskShareRepository implements TaskShareRepositoryInterface
{
    public function createToken(Task $task, int $expiryDays = 7): ?TaskShareToken
    {
        return TaskShareToken::create([
            'task_id' => $task->id,
            'token' => Str::random(32),
            'expires_at' => now()->addDays($expiryDays)->toDateTimeString(),
        ]);
    }

    public function findValidToken(string $token): ?TaskShareToken
    {
        return TaskShareToken::query()
            ->with('task')
            ->where('token', $token)
            ->where('expires_at', '>', now()->toDateTimeString())
            ->first();
    }
}
