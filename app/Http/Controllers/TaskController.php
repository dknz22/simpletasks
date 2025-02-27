<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Jobs\DeleteUnassignedTask;
use App\Models\Task;
use App\Notifications\TaskStatusUpdated;
use Illuminate\Http\JsonResponse;

/**
 * Controller for managing tasks.
 */
class TaskController extends Controller
{
    /**
     * Retrieve a paginated list of tasks with optional filtering and sorting.
     *
     * Query Parameters:
     * - status (string, optional) - Filter by task status ('to_do', 'in_progress', 'done').
     * - created_from (date, optional) - Filter tasks created on or after this date.
     * - created_to (date, optional) - Filter tasks created on or before this date.
     * - sort_by (string, optional) - Sort by a specific field ('id', 'title', 'status', 'created_at').
     * - sort_order (string, optional) - Sorting order ('asc' or 'desc').
     *
     * @param FilterTaskRequest $request
     * @return JsonResponse
     */
    public function index(FilterTaskRequest $request): JsonResponse
    {
        $tasks = Task::with('employees')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('created_from'), fn($query) => $query->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn($query) => $query->whereDate('created_at', '<=', $request->created_to))
            ->orderBy($request->input('sort_by', 'id'), $request->input('sort_order', 'asc'))
            ->paginate(10)
            ->appends($request->query());
            
        $tasks = collect($tasks)->except([
            'links', 'first_page_url', 'last_page_url', 'next_page_url', 'path', 'prev_page_url'
        ]);

        return response()->json($tasks);
    }

    /**
     * Create a new task.
     *
     * Expected JSON payload:
     * {
     *   "title": "Task Title",
     *   "description": "Task description", // optional
     *   "status": "to_do" // or "in_progress", "done"
     * }
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        // Schedule task deletion if no employees are assigned within 2 minutes.
        DeleteUnassignedTask::dispatch($task->id)->delay(now()->addMinutes(2));

        return response()->json($task, 201);
    }

    /**
     * Retrieve a specific task along with assigned employees.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load('employees'));
    }

    /**
     * Update an existing task.
     *
     * Expected JSON payload (partial update allowed):
     * {
     *   "title": "Updated Title", // optional
     *   "description": "Updated description", // optional
     *   "status": "in_progress" // or "to_do", "done" (optional)
     * }
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $oldStatus = $task->status;
        $task->update($request->validated());

        // Notify employees if task status changes to "in_progress" or "done".
        if ($request->has('status') && $oldStatus !== $task->status && in_array($task->status, ['in_progress', 'done'])) {
            $task->employees->each->notify(new TaskStatusUpdated($task));
        }

        return response()->json($task);
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    /**
     * Assign employees to a task.
     *
     * Expected JSON payload:
     * {
     *   "employee_ids": [1, 2, 3] // Array of employee IDs
     * }
     *
     * @param Task $task
     * @param AssignTaskRequest $request
     * @return JsonResponse
     */
    public function assign(Task $task, AssignTaskRequest $request): JsonResponse
    {
        $task->employees()->sync($request->validated()['employee_ids']);
        return response()->json(['message' => 'Task assigned successfully']);
    }

    /**
     * Retrieve tasks grouped by status.
     *
     * @return JsonResponse
     */
    public function groupByStatus(): JsonResponse
    {
        $tasks = Task::with(['employees:id,name,email'])
            ->get()
            ->makeHidden(['created_at', 'updated_at'])
            ->each(fn($task) => $task->employees->makeHidden('pivot'))
            ->groupBy('status');

        return response()->json($tasks);
    }    
}
