<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

final class TaskController extends Controller
{
    public function index(): Factory|View|Application|\Illuminate\View\View
    {
        return view('tasks.index', [
            'tasks' => Task::all(),
        ]);
    }
}
