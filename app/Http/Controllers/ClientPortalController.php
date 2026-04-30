<?php

namespace App\Http\Controllers;

use App\Http\Requests\Portal\StoreContractRequest;
use App\Models\Company;
use App\Models\Contract;
use Carbon\Carbon;

class ClientPortalController extends Controller
{
    public function dashboard()
    {
        $client = auth()->user()->client;
        $contracts = $client->contracts()->latest()->get();
        $invoices = $client->invoices()->latest()->get();

        return view('pages.client.dashboard', compact('client', 'contracts', 'invoices'));
    }

    public function createContractRequest()
    {
        $client = auth()->user()->client;
        $company = $client->company;

        return view('pages.client.contract_request', compact('client', 'company'));
    }

    public function storeContractRequest(StoreContractRequest $request)
    {
        $validated = $request->validated();

        $client = auth()->user()->client;
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $duration = $startDate->diffInMonths($endDate);

        $client->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        if ($validated['client_type'] === 'company') {
            Company::updateOrCreate(
                ['client_id' => $client->id],
                [
                    'company_name' => $validated['company_name'],
                    'ice' => $validated['ice'],
                    'rc' => $validated['rc'],
                    'rce' => $validated['rce'] ?? null,
                    'if' => $validated['if'],
                    'legal_form' => $validated['legal_form'] ?? null,
                    'activity' => $validated['activity'] ?? null,
                    'headquarters_address' => $validated['headquarters_address'] ?? null,
                ]
            );
        }

        Contract::create([
            'client_id' => $client->id,
            'type' => $validated['contract_type'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $duration ?: 1,
            'price' => config('pricing.contract_creation.base_price_per_year', 800), 
            'cin_path' => $request->hasFile('cin_file') ? $request->file('cin_file')->store('contracts/cin', 'public') : null,
            'certificat_path' => $request->hasFile('certificat_file') ? $request->file('certificat_file')->store('contracts/certificat', 'public') : null,
            'status' => 'pending',
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Votre demande de contrat a ete envoyee a l admin.');
    }
}
