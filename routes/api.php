<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;

Route::post('employees/{employee}/roles', [EmployeeController::class, 'updateRoles']);

Route::apiResource('employees', EmployeeController::class);

Route::get('/tasks/grouped', [TaskController::class, 'groupByStatus']);

Route::apiResource('tasks', TaskController::class)
    ->only(['index', 'show', 'update', 'destroy']);

Route::post('tasks', [TaskController::class, 'store'])
    ->middleware('throttle:2,1');

Route::post('tasks/{task}/assign', [TaskController::class, 'assign']);
