<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogPostRequest extends FormRequest
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
            'slug' => 'required|alpha_dash|unique:blog_posts,slug,' . $this->post->id,
            'content' => 'required|min:2',
            'meta_description' => 'nullable|string|max:320',
            'meta_title' => 'nullable|string|max:160',
            'lang' => 'required|in:' . getAllLanguagesValidation(),
            'category' => 'numeric|required',
            'tags' => 'nullable|max:250',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'boolean|required',
            'description' => 'string|required|max:320',
        ];
    }
}