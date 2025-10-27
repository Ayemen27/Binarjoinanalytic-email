<?php

namespace App\Http\Requests\Filter;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'q' => 'nullable|string|max:255',
            'lang' => 'nullable|in:all,' . getAllLanguagesValidation(),
            'order_type' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1|max:250',
            'page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:name,created_at,title',
        ];
    }
}
