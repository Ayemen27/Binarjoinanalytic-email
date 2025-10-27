<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
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
            'icon' => [
                'required',
                'max:255',
                'regex:/^<i class="[^"]*"\s*><\/i>$/'
            ],
            'title' => 'required|max:255|min:2',
            'content' => 'required|max:1000|min:10',
            'lang' => 'required|in:' . getAllLanguagesValidation(),
        ];
    }
}
