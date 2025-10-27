<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
            'title' => 'required|max:255|min:2',
            'slug' => 'required|unique:pages|alpha_dash',
            'content' => 'required|min:2',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'lang' => 'required|in:' . getAllLanguagesValidation(),
            'status' => 'boolean|required',
        ];
    }
}
