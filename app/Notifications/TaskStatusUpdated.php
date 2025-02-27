<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification for updating the task status.
 */
class TaskStatusUpdated extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     *
     * @param mixed $task The task that has been updated.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification delivery channels.
     *
     * @param mixed $notifiable The entity that will receive the notification.
     * @return array<string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param mixed $notifiable The entity that will receive the notification.
     * @return array<string, string>
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Задача #{$this->task->id} была переведена в статус {$this->task->status}"
        ];
    }
}
