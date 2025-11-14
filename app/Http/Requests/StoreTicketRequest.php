<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:priorities,id',
            'status_id' => 'sometimes|exists:statuses,id',
            'user_id' => 'sometimes|exists:users,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'labels' => 'required|array',
            'labels.*' => 'exists:labels,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'priority_id.required' => 'Please select a priority.',
            'categories.required' => 'Please select at least one category.',
            'labels.required' => 'Please select at least one label.',
            'attachments.*.max' => 'Each attachment must not exceed 10MB.',
            'attachments.*.mimes' => 'Allowed attachment types are: jpg, jpeg, png, pdf, doc, docx, txt.',
        ];
    }
}
