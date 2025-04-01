<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

#[ScopedBy([UserScope::class])]
final class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(TaskShareToken::class);
    }

    public function createShareToken(): void
    {
        $this->shares()->create([
            'task_id' => $this->id,
            'token' => Str::random(40),
            'expires_at' => now()->addMinutes(3)->toDateTime(),
        ]);
    }
}
