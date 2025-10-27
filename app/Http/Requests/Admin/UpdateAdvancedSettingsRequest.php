<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvancedSettingsRequest  extends FormRequest
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
            'imap_host' => 'required',
            'imap_user' => 'required',
            'imap_port' => 'required|numeric',
            'imap_pass' => 'required',
            'validate_certificates' => 'boolean',
            'fetch_messages' => 'numeric|required|min:5',
            'email_length' => 'numeric|required|min:5|max:20',
            'email_lifetime' => 'numeric|required|min:1',
            'time_unit' => 'required|in:minute,hour,day',
            'imap_retention_days' => 'numeric|required|min:1',
            'history_retention_days' => 'numeric|required|min:1',
            // Add more validation rules for advanced settings as needed
        ];
    }
}