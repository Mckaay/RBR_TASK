<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class TaskController extends Controller
{
    public function index(Request $request): Factory|View|Application|\Illuminate\View\View
    {
        $request->validate([
            'status' => 'nullable|string|in:all,' . implode(',', array_map(fn($status) => $status->value, Status::cases())),
            'priority' => 'nullable|string|in:all,' . implode(',', array_map(fn($priority) => $priority->value, Priority::cases())),
        ]);

        $query = Task::query();

        if ($request->has('status') && 'all' !== $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && 'all' !== $request->priority) {
            $query->where('priority', $request->priority);
        }

        return view('task.index', [
            'tasks' => $query->get(),
            'statuses' => Status::cases(),
            'priorities' => Priority::cases(),
            'filters' => [
                'status' => $request->status ?? 'all',
                'priority' => $request->priority ?? 'all',
            ],
        ]);
    }

    public function show(Task $task): Factory|View|Application|\Illuminate\View\View
    {
        return view('task.show', [
            'task' => $task,
        ]);
    }

    public function showCreateForm(): Factory|View|Application|\Illuminate\View\View
    {
        return view('task.create');
    }

    public function showUpdateForm(Task $task): Factory|View|Application|\Illuminate\View\View
    {
        return view('task.edit', [
            'task' => $task,
        ]);
    }

    public function update(StoreTaskRequest $request, Task $task): RedirectResponse
    {
        $updated = $task->update($request->validated());

        if ( ! $updated) {
            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task Updated successfully!');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());
        if ( ! $task) {
            return redirect()->back()->with('error', 'Failed to create task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task created successfully!');
    }

    public function delete(Task $task): RedirectResponse
    {
        $deleted = $task->delete();

        if ( ! $deleted) {
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task deleted successfully!');
    }
}
