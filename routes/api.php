<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('tasks', TaskController::class);
Route::post('tasks/{task}/assign', [TaskController::class, 'assign']);