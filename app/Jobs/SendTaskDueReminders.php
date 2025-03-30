<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Status;
use App\Models\Scopes\UserScope;
use App\Models\Task;
use App\Notifications\TaskDueReminder;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

final class SendTaskDueReminders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $tomorrowDate = Carbon::now()->addDay()->toDateString();

        Task::query()
            ->with('user')
            ->where('due_date', '=', $tomorrowDate)
            ->where('status', '!=', Status::DONE->value)
            ->withoutGlobalScope(UserScope::class)
            ->chunk(200, function ($tasks): void {
                foreach ($tasks as $task) {
                    $user = $task->user;
                    if ($user) {
                        Notification::send($user, new TaskDueReminder($task));
                    }
                }
            });
    }
}
