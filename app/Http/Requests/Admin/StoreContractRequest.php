<?php

namespace App\Http\Requests\Admin;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'company_name' => 'required|string|max:255',
            'ice' => 'required|string|max:50',
            'rc' => 'required|string|max:50',
            'if' => 'required|string|max:50',
            'ref' => 'nullable|string|max:50',
            'date_creation' => 'nullable|date',
            'contract_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'interlocuteur' => 'nullable|string|max:255',
            'remarque' => 'nullable|string',
            'montant_ht' => 'nullable|numeric|min:0',
            'cin_file' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'certificat_file' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ];
    }
}
