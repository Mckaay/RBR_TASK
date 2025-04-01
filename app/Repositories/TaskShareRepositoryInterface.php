<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskShareToken;

interface TaskShareRepositoryInterface
{
    public function createToken(Task $task, int $expiryDays = 7): ?TaskShareToken;
    public function findValidToken(string $token): ?TaskShareToken;
}
