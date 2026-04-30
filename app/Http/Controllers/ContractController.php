<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Http\Requests\Admin\StoreContractRequest;
use App\Http\Requests\Admin\RenewContractRequest;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\AccountingEntry;
use App\Mail\ContractMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Services\AccountingService;
use App\Services\ContractService;

class ContractController extends Controller
{
    protected $accountingService;
    protected $contractService;

    public function __construct(AccountingService $accountingService, ContractService $contractService)
    {
        $this->accountingService = $accountingService;
        $this->contractService = $contractService;
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $query = Contract::with(['client.company', 'invoice'])->latest();
 
        if ($search) {
            $query->whereHas('client', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if (in_array($status, ['pending', 'active'], true)) {
            $query->where('status', $status);
        }
 
        $contracts = $query->paginate(15)->withQueryString();
        $counts = [
            'all' => Contract::count(),
            'pending' => Contract::where('status', 'pending')->count(),
            'active' => Contract::where('status', 'active')->count(),
        ];

        return view('pages.contracts.index', compact('contracts', 'counts'));
    }

    public function pending(Request $request)
    {
        $search = $request->input('search');
        $query = Contract::with(['client.company', 'invoice'])
            ->where('status', 'pending');

        if ($search) {
            $query->whereHas('client', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $contracts = $query->latest()->paginate(15)->withQueryString();
        $counts = [
            'all' => Contract::count(),
            'pending' => Contract::where('status', 'pending')->count(),
            'active' => Contract::where('status', 'active')->count(),
        ];

        return view('pages.contracts.index', compact('contracts', 'counts'));
    }

    public function create()
    {
        return view('pages.contracts.create');
    }

    public function store(StoreContractRequest $request)
    {
        $validated = $request->validated();

        // 1. Store Client

        // 1. Store Client
        $client = Client::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'cin' => $validated['cin'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
        ]);

        // 2. Store Company
        Company::create([
            'client_id' => $client->id,
            'company_name' => $validated['company_name'],
            'ice' => $validated['ice'],
            'rc' => $validated['rc'],
            'if' => $validated['if'],
        ]);

        // Create Contract Logic
        $pricing = $this->contractService->calculatePricing(Carbon::parse($validated['start_date']), Carbon::parse($validated['end_date']), false);
        $price = $pricing['price'];
        $duration = $pricing['duration'];

        // Upload Files
        $cinPath = $request->file('cin_file')->store('contracts/cin', 'public');
        $certPath = $request->file('certificat_file')->store('contracts/certificat', 'public');

        // 3. Store Contract
        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => $validated['contract_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration' => $duration,
            'price' => $price,
            'cin_path' => $cinPath,
            'certificat_path' => $certPath,
        ]);

        // 4. Activate Contract via Service (Invoice + Accounting)
        $this->contractService->activateContract($contract);

        // Redirect to show the contract
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Contract generated successfully.');
    }

    public function show($id)
    {
        $contract = Contract::with(['client.company', 'invoice'])->findOrFail($id);
        
        return view('pages.contracts.show', compact('contract'));
    }

    public function approve($id)
    {
        $contract = Contract::with(['client.company'])->findOrFail($id);

        if ($contract->status !== 'pending') {
            return redirect()->back()->with('error', 'Ce contrat est déjà actif.');
        }

        $this->contractService->activateContract($contract);

        return redirect()->route('contracts.show', $contract->id)->with('success', 'La demande a été approuvée, le contrat est maintenant actif et la facture a été générée.');
    }

    /**
     * Send contract PDF by email to the client.
     */
    /**
     * Send contract PDF by email to the client.
     */
    public function sendEmail($id)
    {
        $contract = Contract::with(['client.company'])->findOrFail($id);
        $clientEmail = $contract->client->email;

        if (!$clientEmail) {
            return redirect()->back()->with('error', 'Ce client n\'a pas d\'adresse email enregistrée.');
        }

        try {
            Mail::to($clientEmail)->send(new ContractMail($contract));
            return redirect()->back()->with('success', 'Le contrat a été envoyé par email à ' . $clientEmail . ' avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    public function renew($id)
    {
        $contract = Contract::with(['client.company'])->findOrFail($id);
        
        // Prevent renewing a contract that already has an active renewal
        $existingRenewal = Contract::where('parent_id', $contract->id)->whereIn('status', ['pending', 'active'])->exists();
        if ($existingRenewal) {
            return redirect()->back()->with('error', 'Ce contrat a déjà un renouvellement en cours ou actif.');
        }

        return view('pages.contracts.renew', compact('contract'));
    }

    public function storeRenewal(RenewContractRequest $request, $id)
    {
        $originalContract = Contract::with(['client.company'])->findOrFail($id);

        $validated = $request->validated();

        $pricing = $this->contractService->calculatePricing(Carbon::parse($validated['start_date']), Carbon::parse($validated['end_date']), true);
        $price = $pricing['price'];
        $duration = $pricing['duration'];

        // Create the new contract record
        $renewalContract = Contract::create([
            'client_id' => $originalContract->client_id,
            'parent_id' => $originalContract->id,
            'type' => $originalContract->type . ' (Renouvellement)',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration' => $duration,
            'price' => $price,
            'cin_path' => $originalContract->cin_path,
            'certificat_path' => $originalContract->certificat_path,
            'status' => 'active', 
            'is_renewal' => true,
        ]);

        // Activate Renewal via Service
        $this->contractService->activateContract($renewalContract);

        return redirect()->route('contracts.show', $renewalContract->id)->with('success', 'Le contrat a été renouvelé avec succès. Une nouvelle facture de ' . $price . ' dh a été générée.');
    }

    public function notifyExpiry($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $daysLeft = now()->diffInDays($contract->end_date, false);

        if (!$contract->client->email) {
            return redirect()->back()->with('error', 'Le client n\'a pas d\'adresse email.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($contract->client->email)->send(new \App\Mail\ContractExpiryMail($contract, $daysLeft));
            return redirect()->back()->with('success', 'Notification envoyée avec succès au client.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $contract = Contract::with('client.company')->findOrFail($id);
        return view('pages.contracts.edit', compact('contract'));
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        
        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active'
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract->id)->with('success', 'Contrat mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $contract = Contract::with(['invoice.accountingEntries'])->findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function() use ($contract) {
            if ($contract->invoice) {
                // Delete accounting entries first
                $contract->invoice->accountingEntries()->delete();
                // Then delete invoice
                $contract->invoice->delete();
            }
            // Finally delete contract
            $contract->delete();
        });

        return redirect()->route('contracts.index')->with('success', 'Le contrat et ses documents associés ont été supprimés avec succès.');
    }
}
