<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\FilterRequest;
use App\Jobs\DeleteUnassignedTask;
use App\Models\Task;
use App\Notifications\TaskStatusUpdated;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $tasks = Task::with('employees')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('created_from'), fn($query) => $query->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn($query) => $query->whereDate('created_at', '<=', $request->created_to))
            ->orderBy($request->input('sort_by', 'id'), $request->input('sort_order', 'asc'))
            ->paginate(10)
            ->appends($request->query())
            ->except(['links', 'first_page_url', 'last_page_url', 'next_page_url', 'path', 'prev_page_url']);

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        DeleteUnassignedTask::dispatch($task->id)->delay(now()->addMinutes(2));

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load('employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $oldStatus = $task->status;
        $task->update($request->validated());

        if ($request->has('status') && $oldStatus !== $task->status && in_array($task->status, ['in_progress', 'done'])) {
            $task->employees->each->notify(new TaskStatusUpdated($task));
        }

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    public function assign(Task $task, AssignTaskRequest $request): JsonResponse
    {
        $task->employees()->sync($request->validated()['employee_ids']);
        return response()->json(['message' => 'Task assigned successfully']);
    }

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
