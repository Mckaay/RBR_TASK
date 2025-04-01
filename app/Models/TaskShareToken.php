<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TaskShareToken extends Model
{
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
