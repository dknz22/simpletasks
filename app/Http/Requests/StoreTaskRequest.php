<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating task creation.
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Define the validation rules for storing a new task.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255', // Task title (mandatory, max length: 255)
            'description' => 'nullable|string', // Optional task description
            'status' => 'required|in:to_do,in_progress,done', // Task status must be one of the predefined values
        ];
    }
}
