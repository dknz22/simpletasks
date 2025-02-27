<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for filtering and sorting employee.
 */
class FilterEmployeeRequest extends FormRequest
{
    /**
     * Define the validation rules for filtering and sorting.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|in:active,on_leave',
            'sort_by' => 'sometimes|string|in:id,name,email,status,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc'
        ];
    }
}
