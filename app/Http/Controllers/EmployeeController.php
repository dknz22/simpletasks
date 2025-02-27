<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Employee::with('tasks')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request) {
        return Employee::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee) {
        return $employee->load('tasks');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee) {
        $employee->update($request->validated());
        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee) {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted']);
    }
}
