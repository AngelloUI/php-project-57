<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status_id' => ['required', 'integer', 'exists:task_statuses,id'],
            'assigned_to_id' => ['nullable', 'integer', 'exists:users,id'],
            'labels' => ['sometimes', 'array'],
            'labels.*' => ['integer', 'exists:labels,id'],
        ];
    }
}

