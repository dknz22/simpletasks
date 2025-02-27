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
Route::prefix('employees')->group(function () {
    // Assign roles to a specific employee
    Route::post('{employee}/roles', [EmployeeController::class, 'updateRoles']);

    // RESTful routes for employees (index, show, store, update, destroy)
    Route::apiResource('/', EmployeeController::class);
});

/*
|--------------------------------------------------------------------------
| Task Routes
|--------------------------------------------------------------------------
|
| API endpoints related to task management, including task assignment,
| status grouping, and standard CRUD operations.
|
*/
Route::prefix('tasks')->group(function () {
    // Retrieve tasks grouped by status
    Route::get('grouped', [TaskController::class, 'groupByStatus']);

    // Create a new task with rate limiting (max 2 requests per minute per IP)
    Route::post('/', [TaskController::class, 'store'])
        ->middleware('throttle:2,1');

    // Assign employees to a task
    Route::post('{task}/assign', [TaskController::class, 'assign']);

    // RESTful routes for tasks (index, show, update, destroy)
    Route::apiResource('/', TaskController::class)
        ->only(['index', 'show', 'update', 'destroy']);
});
