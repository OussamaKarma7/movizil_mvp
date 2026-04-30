<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contract;
use Carbon\Carbon;

class PublicFormController extends Controller
{
    public function create()
    {
        return view('pages.public_form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'required|in:individual,company',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'company_name' => 'nullable|required_if:client_type,company|string|max:255',
            'ice' => 'nullable|required_if:client_type,company|string|max:50',
            'rc' => 'nullable|required_if:client_type,company|string|max:50',
            'if' => 'nullable|required_if:client_type,company|string|max:50',
            'contract_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'cin_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'certificat_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $client = Client::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'cin' => $validated['cin'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
        ]);

        if ($validated['client_type'] === 'company') {
            Company::create([
                'client_id' => $client->id,
                'company_name' => $validated['company_name'],
                'ice' => $validated['ice'],
                'rc' => $validated['rc'],
                'if' => $validated['if'],
            ]);
        }

        $price = $validated['duration'] * 100;
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($validated['duration']);

        $cinPath = $request->hasFile('cin_file') ? $request->file('cin_file')->store('contracts/cin', 'public') : null;
        $certPath = $request->hasFile('certificat_file') ? $request->file('certificat_file')->store('contracts/certificat', 'public') : null;

        Contract::create([
            'client_id' => $client->id,
            'type' => $validated['contract_type'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $validated['duration'],
            'price' => $price,
            'cin_path' => $cinPath,
            'certificat_path' => $certPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Votre demande de contrat a été soumise avec succès. Nous reviendrons vers vous très prochainement.');
    }
}
