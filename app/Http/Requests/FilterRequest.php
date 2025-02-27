<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for filtering and sorting tasks or employees.
 */
class FilterRequest extends FormRequest
{
    /**
     * Define the validation rules for filtering and sorting.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:to_do,in_progress,done', // Optional task status filter
            'created_from' => 'nullable|date', // Optional start date filter
            'created_to' => 'nullable|date', // Optional end date filter
            'sort_by' => 'nullable|string|in:id,title,status,created_at', // Optional sorting field
            'sort_order' => 'nullable|string|in:asc,desc', // Sorting order (ascending or descending)
        ];
    }
}
