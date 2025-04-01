<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskShareToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TaskShareTokenController extends Controller
{
    public function create(Request $request, Task $task)
    {
        if (Gate::denies('share', $task)) {
            return redirect()->route('task.index')
                ->with('error', 'You do not have permission to share this task.');
        }

        $token = Str::random(32);
        $shareToken = TaskShareToken::create([
            'task_id' => $task->id,
            'token' => $token,
            'expires_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        if (!$shareToken) {
            return redirect()->route('task.show', $task)
                ->with('error', 'Something went wrong');
        }

        return redirect()->route('task.show', $task)
            ->with('success', 'Share link was generated successfully.')
            ->with('share_url', route('task.share', $token));
    }

    public function share(string $token)
    {
        $taskShareToken = TaskShareToken::query()
            ->with('task')
            ->where('token', $token)
            ->where('expires_at', '>', now()->toDateTimeString())
            ->first();

        if ( ! $taskShareToken) {
            return redirect()->route('task.index')->with('error', 'Link has expired. Please try again.');
        }

        return view('task.share', [
            'task' => $taskShareToken->task,
        ]);
    }
}

