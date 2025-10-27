<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
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
        $type = strtolower($this->type) === 'html' ? 1 : 0;


        return [
            'title' => 'required_if:type,1|max:255',
            'content' => 'required_if:type,1',
            'lang' => 'required_if:type,1|in:' . getAllLanguagesValidation(),
            'status' => 'required_if:type,1|boolean',
        ];
    }
}
