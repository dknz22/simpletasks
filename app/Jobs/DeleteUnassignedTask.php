<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job to delete a task if it remains unassigned after a certain delay.
 */
class DeleteUnassignedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $taskId;

    /**
     * Create a new job instance.
     *
     * @param int $taskId The ID of the task to be checked and deleted if unassigned.
     */
    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * Execute the job.
     * 
     * Finds the task by ID and deletes it if no employees are assigned.
     */
    public function handle()
    {
        $task = Task::find($this->taskId);

        if ($task && !$task->employees()->exists()) {
            $task->delete();
        }
    }
}
