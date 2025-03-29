<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
            'priority' => ['required', Rule::enum(Priority::class)],
            'due_date' => ['required', 'date','after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The task must be assigned to a user.',
            'user_id.exists' => 'The selected user does not exist.',
            'name.required' => 'The task name is required.',
            'name.max' => 'The task name cannot exceed 255 characters.',
            'status.required' => 'The task status is required.',
            'status.enum' => 'The selected status is invalid.',
            'priority.required' => 'The task priority is required.',
            'priority.enum' => 'The selected priority is invalid.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'Please provide a valid date format.',
        ];
    }
}
