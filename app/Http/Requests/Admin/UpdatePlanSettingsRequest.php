<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanSettingsRequest  extends FormRequest
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
    public function rules()
    {
        return [
            // guest
            'guest_history' => 'required|integer',
            'guest_attachments' => 'required',
            'guest_premium_domains' => 'required',
            'guest_ads' => 'required',
            // free
            'domains' => 'required|integer',
            'history' => 'required|integer',
            'messages' => 'required|integer',
            'ads' => 'required',
            'attachments' => 'required',
            'premium_domains' => 'required',
        ];
    }
}