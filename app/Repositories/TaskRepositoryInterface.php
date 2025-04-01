<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DataObjects\TaskDTO;
use App\DataObjects\TaskFilterDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAll(TaskFilterDTO $filters): Collection;

    public function find(int $id): ?Task;

    public function create(TaskDTO $taskDTO): ?Task;

    public function update(Task $task, TaskDTO $taskDTO): bool;

    public function delete(Task $task): bool;

    public function getStatuses(): array;

    public function getPriorities(): array;
}
