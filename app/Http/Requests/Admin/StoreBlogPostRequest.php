<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPostRequest extends FormRequest
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
            'title' => 'required|max:160|min:2',
            'slug' => 'required|unique:blog_posts|alpha_dash',
            'content' => 'required|min:2',
            'meta_description' => 'nullable|string|max:320',
            'meta_title' => 'nullable|string|max:160',
            'lang' => 'required|in:' . getAllLanguagesValidation(),
            'category' => 'numeric|required',
            'tags' => 'nullable|max:250',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'boolean|required',
            'description' => 'string|nullable|max:320',
        ];
    }
}
