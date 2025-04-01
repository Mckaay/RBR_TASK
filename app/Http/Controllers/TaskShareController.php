<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskShareRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class TaskShareController extends Controller
{
    private TaskShareRepository $taskShareRepository;

    public function __construct(TaskShareRepository $taskShareRepository)
    {
        $this->taskShareRepository = $taskShareRepository;
    }

    public function store(Request $request, Task $task)
    {
        if (Gate::denies('share', $task)) {
            return redirect()->route('task.index')
                ->with('error', 'You do not have permission to share this task.');
        }

        $shareToken = $this->taskShareRepository->createToken($task);

        if ( ! $shareToken) {
            return redirect()->route('task.show', $task)
                ->with('error', 'Something went wrong');
        }

        return redirect()->route('task.show', $task)
            ->with('success', 'Share link was generated successfully.')
            ->with('share_url', route('task.share', $shareToken->token));
    }

    public function show(string $token)
    {
        $taskShareToken = $this->taskShareRepository->findValidToken($token);

        if ( ! $taskShareToken) {
            return redirect()->route('task.index')->with('error', 'Link has expired. Please try again.');
        }

        return view('task.share', [
            'task' => $taskShareToken->task,
        ]);
    }
}
