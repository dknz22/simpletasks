<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $employees = Employee::with('tasks')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->orderBy($request->input('sort_by', 'id'), $request->input('sort_order', 'asc'))
            ->paginate(10)
            ->appends($request->query())
            ->except(['links', 'first_page_url', 'last_page_url', 'next_page_url', 'path', 'prev_page_url']);

        return response()->json($employees);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = Employee::create($request->validated());
        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json($employee->load('tasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee->update($request->validated());
        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted'], 200);
    }

    public function updateRoles(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $employee->roles()->sync($validated['role_ids']);

        return response()->json(['message' => 'Roles updated successfully']);
    }  
}
