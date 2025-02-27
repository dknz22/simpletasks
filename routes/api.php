<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;

Route::prefix('employees')->group(function () {
    Route::post('{employee}/roles', [EmployeeController::class, 'updateRoles']);
    Route::apiResource('/', EmployeeController::class);
});

Route::prefix('tasks')->group(function () {
    Route::get('grouped', [TaskController::class, 'groupByStatus']);
    Route::post('/', [TaskController::class, 'store'])
        ->middleware('throttle:2,1');
    Route::post('{task}/assign', [TaskController::class, 'assign']);
    Route::apiResource('/', TaskController::class)
        ->only(['index', 'show', 'update', 'destroy']);
});
