<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Http\Request;

final class TaskFilterDTO
{
    public function __construct(
        public readonly ?string $status = 'all',
        public readonly ?string $priority = 'all',
        public readonly ?string $due_date_sort = 'none',
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validatedData = $request->validate([
            'status' => 'nullable|string|in:all,' . implode(',', array_map(fn($status) => $status->value, Status::cases())),
            'priority' => 'nullable|string|in:all,' . implode(',', array_map(fn($priority) => $priority->value, Priority::cases())),
            'due_date_sort' => 'nullable|string|in:none,asc,desc',
        ]);

        return new self(
            $validatedData['status'] ?? 'all',
            $validatedData['priority'] ?? 'all',
            $validatedData['due_date_sort'] ?? 'none',
        );
    }

    public function hasStatusFilter(): bool
    {
        return 'all' !== $this->status;
    }

    public function hasPriorityFilter(): bool
    {
        return 'all' !== $this->priority;
    }

    public function hasDueDateSort(): bool
    {
        return 'none' !== $this->due_date_sort;
    }

    public function getStatusForQuery(): ?string
    {
        return $this->hasStatusFilter() ? $this->status : null;
    }

    public function getPriorityForQuery(): ?string
    {
        return $this->hasPriorityFilter() ? $this->priority : null;
    }

    public function getDueDateSortDirection(): ?string
    {
        return $this->hasDueDateSort() ? $this->due_date_sort : null;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date_sort' => $this->due_date_sort,
        ];
    }
}
