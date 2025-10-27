<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMailSettingsRequest extends FormRequest
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
            'mail_mailer' => 'required|in:smtp,sendmail',
            'mail_host' => 'required_if:mail_mailer,==,smtp',
            'mail_port' => 'required_if:mail_mailer,==,smtp',
            'mail_username' => 'required_if:mail_mailer,==,smtp',
            'mail_password' => 'required_if:mail_mailer,==,smtp',
            'mail_encryption' => 'required_if:mail_mailer,==,smtp|in:tls,ssl',
            'mail_from_name' => 'required_if:mail_mailer,==,smtp',
            'mail_from_address' => 'required|email',
            'mail_to_address' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'mail_mailer.required' => 'The Mailer field is required.',
            'mail_mailer.in' => 'The selected Mailer is invalid.',
            'mail_host.required_if' => 'The Host field is required.',
            'mail_port.required_if' => 'The Port field is required.',
            'mail_port.numeric' => 'The Port must be a number.',
            'mail_username.required_if' => 'The Username field is required.',
            'mail_password.required_if' => 'The Password field is required.',
            'mail_encryption.required_if' => 'The Encryption field is required.',
            'mail_encryption.in' => 'The Encryption field is invalid.',
            'mail_from_name.required_if' => 'The Name field is required.',
            'mail_from_address.required' => 'The Mail Address field is required.',
            'mail_from_address.email' => 'The Mail Address must be a valid email address.',
            'mail_to_address.email' => 'The Mail Address must be a valid email address.',
            'mail_to_address.required' => 'The Mail Address field is required.',
        ];
    }
}