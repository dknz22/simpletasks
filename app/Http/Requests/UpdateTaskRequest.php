<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating task update operations.
 */
class UpdateTaskRequest extends FormRequest
{
    /**
     * Define the validation rules for updating a task.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255', // Title can be updated, max length: 255
            'description' => 'sometimes|string', // Description can be updated if provided
            'status' => 'sometimes|in:to_do,in_progress,done', // Status can be updated, must be a valid option
        ];
    }
}
