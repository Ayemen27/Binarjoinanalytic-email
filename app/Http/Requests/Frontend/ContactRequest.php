<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
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
        $rules = [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ];

        // Check which CAPTCHA is enabled and apply appropriate validation rule
        if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha')) {
            $rules['h-captcha-response'] = 'required|hcaptcha';
        } elseif (getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha')) {
            $rules['g-recaptcha-response'] = 'captcha';
        }

        return $rules;
    }
}
