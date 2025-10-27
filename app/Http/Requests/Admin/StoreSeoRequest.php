<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeoRequest extends FormRequest
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
            'title' => 'required|max:255',
            'lang' => 'required|max:10|unique:seo,lang',
            'description' => 'nullable|max:2000',
            'keyword' => 'nullable|max:2000',
            'author' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];
    }
}