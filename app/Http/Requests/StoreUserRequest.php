<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'role_id' => 'sometimes|nullable|integer|exists:roles,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:6|confirmed';
            $rules['email'] = 'required|email|unique:users,email';
        } else {
            $rules['password'] = 'nullable|string|min:6|confirmed';
            $rules['email'] = 'required|email|unique:users,email,' . $this->route('user')->id;
        }
        return $rules;
    }
}
