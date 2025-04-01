<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;

final class TaskDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly Status $status,
        public readonly Priority $priority,
        public readonly ?string $due_date,
        public readonly int $user_id,
    ) {}

    public static function fromRequest(StoreTaskRequest $request, ?int $userId = null): self
    {
        $validated = $request->validated();

        return new self(
            null,
            $validated['name'],
            $validated['description'],
            Status::from($validated['status']),
            Priority::from($validated['priority']),
            $validated['due_date'] ?? null,
            $userId ?? auth()->id(),
        );
    }

    public static function fromModel(Task $task): self
    {
        return new self(
            $task->id,
            $task->name,
            $task->description,
            Status::from($task->status),
            Priority::from($task->priority),
            $task->due_date,
            $task->user_id,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'due_date' => $this->due_date,
            'user_id' => $this->user_id,
        ];
    }
}
