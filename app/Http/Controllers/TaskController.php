<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\FilterRequest;
use App\Jobs\DeleteUnassignedTask;
use App\Models\Task;
use App\Notifications\TaskStatusUpdated;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request) {
        $query = Task::with('employees');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
    
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
    
        $tasks = $query->paginate(10)->appends($request->query())->toArray();
        unset($tasks['links'], $tasks['first_page_url'], $tasks['last_page_url'], $tasks['next_page_url'], $tasks['path'], $task['prev_page_url']);
    
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request) {
        $task = Task::create($request->validated());

        DeleteUnassignedTask::dispatch($task->id)->delay(now()->addMinutes(2));
    
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task) {
        return $task->load('employees');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $oldStatus = $task->status;
    
        $task->update($request->validated());

        if ($request->has('status') && in_array($task->status, ['in_progress', 'done']) && $oldStatus !== $task->status) {
            foreach ($task->employees as $employee) {
                $employee->notify(new TaskStatusUpdated($task));
            }
        }
    
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task) {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    public function assign(Task $task, AssignTaskRequest $request) {   
        $task->employees()->sync($request->validated()['employee_ids']);
        return response()->json(['message' => 'Task assigned successfully']);
    }

    public function groupByStatus()
    {
        $tasks = Task::with(['employees:id,name,email'])
            ->get()
            ->makeHidden(['created_at', 'updated_at'])
            ->each(fn($task) => $task->employees->makeHidden('pivot'))
            ->groupBy('status');
    
        return response()->json($tasks);
    }       
    
}
