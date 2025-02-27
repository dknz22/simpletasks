<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
|
| API endpoints related to employee management, including role assignment
| and standard CRUD operations.
|
*/
Route::apiResource('employees', EmployeeController::class);

// Assign roles to a specific employee
Route::post('employees/{employee}/roles', [EmployeeController::class, 'updateRoles']);

/*
|--------------------------------------------------------------------------
| Task Routes
|--------------------------------------------------------------------------
|
| API endpoints related to task management, including task assignment,
| status grouping, and standard CRUD operations.
|
*/
// Retrieve tasks grouped by status
Route::get('tasks/grouped', [TaskController::class, 'groupByStatus']);

// Create a new task with rate limiting (max 2 requests per minute per IP)
Route::post('tasks', [TaskController::class, 'store'])
    ->middleware('throttle:2,1');

// Assign employees to a task
Route::post('tasks/{task}/assign', [TaskController::class, 'assign']);

// RESTful routes for tasks (index, show, update, destroy)
Route::apiResource('tasks', TaskController::class)
    ->only(['index', 'show', 'update', 'destroy']);