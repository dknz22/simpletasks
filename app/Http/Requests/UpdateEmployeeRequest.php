<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating employee update operations.
 */
class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Define the validation rules for updating an employee.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255', // Name can be updated, max length: 255
            'email' => 'sometimes|email|unique:employees,email,' . $this->route('employee')->id, // Email must be unique except for the current employee
            'status' => 'sometimes|in:active,on_leave', // Status can be updated, must be either 'active' or 'on_leave'
        ];
    }
}
