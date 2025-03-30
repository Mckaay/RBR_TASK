<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TaskDueReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Task $task) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Reminder: Task Due Tomorrow')
            ->line('Your task "' . $this->task->name . '" is due tomorrow, ' . $this->task->due_date . '.')
            ->line('Description: ' . ($this->task->description ?? 'No description'))
            ->line('Priority: ' . $this->task->priority)
            ->action('View Task', url('/task/' . $this->task->id));
    }

    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
