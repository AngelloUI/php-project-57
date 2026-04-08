<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('task_statuses', 'name')->ignore($this->route('task_status')->id),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('task_statuses.form.name'),
        ];
    }
}


