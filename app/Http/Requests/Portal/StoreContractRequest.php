<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'client_type' => 'required|in:individual,company',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contract_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'company_name' => 'nullable|required_if:client_type,company|string|max:255',
            'ice' => 'nullable|required_if:client_type,company|string|max:50',
            'rc' => 'nullable|required_if:client_type,company|string|max:50',
            'rce' => 'nullable|required_if:client_type,company|string|max:50',
            'if' => 'nullable|required_if:client_type,company|string|max:50',
            'legal_form' => 'nullable|string|max:100',
            'activity' => 'nullable|string|max:255',
            'headquarters_address' => 'nullable|string|max:1000',
            'cin_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'certificat_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ];
    }
}
