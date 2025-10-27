<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'icon' => 'required_without:name|max:255',
            'name' => 'required_without:icon|max:255',
            'url' => 'required|max:255|url',
            'type' => 'required|numeric',
            'lang' => 'required|in:' . getAllLanguagesValidation(),
            'is_external' => 'required|in:0,1',
        ];
    }
}