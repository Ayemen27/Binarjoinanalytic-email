<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'firstname' => "required|max:190",
            'lastname' => "nullable|max:190",
            'email' => 'required|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8|max:190',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'account_status' => "required|in:0,1",
            'email_status' => "required|in:0,1",
        ];
    }
}
