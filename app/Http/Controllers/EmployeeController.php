<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Employee::with('tasks');
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
    
        $employees = $query->paginate(10)->appends($request->query())->toArray();
        unset($employees['links'], $employees['first_page_url'], $employees['last_page_url'], $employees['next_page_url'], $employees['path'], $employees['prev_page_url']);
    
        return response()->json($employees);
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

    public function updateRoles(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);
    
        $employee->roles()->sync($validated['role_ids']);
    
        return response()->json(['message' => 'Roles updated successfully']);
    }    
}
