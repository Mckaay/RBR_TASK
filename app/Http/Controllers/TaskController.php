<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataObjects\TaskDTO;
use App\DataObjects\TaskFilterDTO;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class TaskController extends Controller
{
    public function __construct(
        private TaskRepository $taskRepository,
    ) {}

    public function index(Request $request): View
    {
        $filters = TaskFilterDTO::fromRequest($request);

        return view('task.index', [
            'tasks' => $this->taskRepository->getAll($filters),
            'statuses' => $this->taskRepository->getStatuses(),
            'priorities' => $this->taskRepository->getPriorities(),
            'filters' => $filters->toArray(),
        ]);
    }

    public function show(Task $task): View
    {
        return view('task.show', ['task' => $task]);
    }

    public function create(): View
    {
        return view('task.create', [
            'statuses' => $this->taskRepository->getStatuses(),
            'priorities' => $this->taskRepository->getPriorities(),
        ]);
    }

    public function edit(Task $task): View
    {
        return view('task.edit', [
            'task' => $task,
            'statuses' => $this->taskRepository->getStatuses(),
            'priorities' => $this->taskRepository->getPriorities(),
        ]);
    }

    public function update(StoreTaskRequest $request, Task $task): RedirectResponse
    {
        $taskDTO = TaskDTO::fromRequest($request);

        if ( ! $this->taskRepository->update($task, $taskDTO)) {
            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task updated successfully!');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $taskDTO = TaskDTO::fromRequest($request);
        $task = $this->taskRepository->create($taskDTO);

        if ( ! $task) {
            return redirect()->back()->with('error', 'Failed to create task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task created successfully!');
    }

    public function destroy(Task $task): RedirectResponse
    {
        if ( ! $this->taskRepository->delete($task)) {
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }

        return redirect()->route('task.index')->with('success', 'Task deleted successfully!');
    }
}
