<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DataObjects\TaskDTO;
use App\DataObjects\TaskFilterDTO;
use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

final class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(TaskFilterDTO $filters): Collection
    {
        $query = Task::query();

        if ($filters->hasStatusFilter()) {
            $query->where('status', $filters->getStatusForQuery());
        }

        if ($filters->hasPriorityFilter()) {
            $query->where('priority', $filters->getPriorityForQuery());
        }

        if ($filters->hasDueDateSort()) {
            $query->orderBy('due_date', $filters->getDueDateSortDirection());
        }

        return $query->get();
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(TaskDTO $taskDTO): ?Task
    {
        return Task::create($taskDTO->toArray());
    }

    public function update(Task $task, TaskDTO $taskDTO): bool
    {
        return $task->update($taskDTO->toArray());
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function getStatuses(): array
    {
        return Status::cases();
    }

    public function getPriorities(): array
    {
        return Priority::cases();
    }
}
