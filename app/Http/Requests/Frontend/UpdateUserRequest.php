<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        return [
            'firstname' => "required|max:190",
            'lastname' => "required|max:190",
            'email' => "required|unique:users,email," . $user->id,
            'avatar' => "nullable|image|mimes:png,jpg,jpeg,webp|max:2048",
        ];
    }
}
