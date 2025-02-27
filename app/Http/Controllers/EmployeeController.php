<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for managing employees.
 */
class EmployeeController extends Controller
{
    /**
     * Retrieve a paginated list of employees with optional filtering and sorting.
     *
     * Query Parameters:
     * - status (string, optional) - Filter by employee status ('active', 'on_leave').
     * - sort_by (string, optional) - Sort by a specific field ('id', 'name', 'email', 'status', 'created_at').
     * - sort_order (string, optional) - Sorting order ('asc' or 'desc').
     *
     * @param FilterRequest $request
     * @return JsonResponse
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
     * Create a new employee.
     *
     * Expected JSON payload:
     * {
     *   "name": "John Doe",
     *   "email": "john.doe@example.com",
     *   "status": "active" // or "on_leave"
     * }
     *
     * @param StoreEmployeeRequest $request
     * @return JsonResponse
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = Employee::create($request->validated());
        return response()->json($employee, 201);
    }


    /**
     * Retrieve a specific employee along with assigned tasks.
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json($employee->load('tasks'));
    }

    /**
     * Update an existing employee's details.
     *
     * Expected JSON payload (partial update allowed):
     * {
     *   "name": "Updated Name", // optional
     *   "email": "updated.email@example.com", // optional
     *   "status": "on_leave" // optional
     * }
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee->update($request->validated());
        return response()->json($employee);
    }

    /**
     * Delete an employee.
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted'], 200);
    }

    /**
     * Update the roles assigned to an employee.
     *
     * Expected JSON payload:
     * {
     *   "role_ids": [1, 2] // Array of role IDs
     * }
     *
     * @param Request $request
     * @param Employee $employee
     * @return JsonResponse
     */
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
