<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;


class StoreAdminRequest extends FormRequest
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
            'lastname' => "required|max:190",
            'email' => "required|unique:admins,email",
            'password' => "required|min:8|max:190",
            'avatar' => "nullable|image|mimes:png,jpg,jpeg,webp|max:2048",
        ];
    }
}