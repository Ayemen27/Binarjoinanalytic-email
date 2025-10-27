<?php

namespace App\Http\Requests\Admin;

use App\Models\Domain;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'domain' => extractDomain($this->input('domain'))
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $domainId = $this->route('domain');

        return [
            'domain' => 'required|unique:domains,domain,' . $domainId->id,
            'type' => 'required|integer',
            'status' => 'required|integer',
            'user_id' => 'required_if:type,2|integer|exists:users,id',
        ];
    }
}