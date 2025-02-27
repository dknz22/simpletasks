<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating employee creation.
 */
class StoreEmployeeRequest extends FormRequest
{
    /**
     * Define the validation rules for storing a new employee.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Employee name (mandatory, max length: 255)
            'email' => 'required|email|unique:employees', // Unique and valid email
            'status' => 'required|in:active,on_leave', // Employee status must be 'active' or 'on_leave'
        ];
    }
}
