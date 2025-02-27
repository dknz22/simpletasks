<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Task::with('employees')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request) {
        return Task::create($request->validated());
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
    public function update(UpdateTaskRequest $request, Task $task) {
        $task->update($request->validated());
        return $task;
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
        return response()->json(['message' => 'Task assigned']);
    }
}
