<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:to_do,in_progress,done',
            'created_from' => 'nullable|date',
            'created_to' => 'nullable|date',
            'sort_by' => 'nullable|string|in:id,title,status,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }
}
