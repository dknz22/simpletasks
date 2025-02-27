<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating task assignment requests.
 */
class AssignTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'employee_ids' => 'sometimes|array', // Optional array of employee IDs
            'employee_ids.*' => [
                'exists:employees,id', // Ensures each ID exists in the employees table
                function ($attribute, $value, $fail) {
                    $employee = \App\Models\Employee::find($value);
                    if ($employee && $employee->status === 'on_leave') {
                        $fail("Employee ID {$value} is on leave and cannot be assigned a task.");
                    }
                },
            ],
        ];
    }
    
}
