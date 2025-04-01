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
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validatedData = $request->validate([
            'status' => 'nullable|string|in:all,' . implode(',', array_map(fn($status) => $status->value, Status::cases())),
            'priority' => 'nullable|string|in:all,' . implode(',', array_map(fn($priority) => $priority->value, Priority::cases())),
        ]);

        return new self(
            $validatedData['status'] ?? 'all',
            $validatedData['priority'] ?? 'all',
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

    public function getStatusForQuery(): ?string
    {
        return $this->hasStatusFilter() ? $this->status : null;
    }

    public function getPriorityForQuery(): ?string
    {
        return $this->hasPriorityFilter() ? $this->priority : null;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'priority' => $this->priority,
        ];
    }
}
