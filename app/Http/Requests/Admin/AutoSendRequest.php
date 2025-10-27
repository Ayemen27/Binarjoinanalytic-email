<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;


class AutoSendRequest  extends FormRequest
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
            'lang' => ['required', 'array', 'min:1'], // Must be an array with at least 1 element
            'collections' => [
                'required_without:options',
                'array',
                'min:1'
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'lang.required' => 'The language selection is required.',
            'lang.array' => 'The language must be an array.',
            'lang.min' => 'At least one language must be selected.',
            'collections.required_unless' => 'Collections are required if neither FAQs nor Features are provided.',
            'collections.array' => 'Collections must be an array.',
            'collections.min' => 'At least one collection must be selected.',
        ];
    }
}
