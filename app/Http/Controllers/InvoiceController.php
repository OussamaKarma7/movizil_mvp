<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('contract.client');
        $status = $request->input('status');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('contract.client', function($q) use ($search) {
                      $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('company_name', 'LIKE', "%{$search}%");
                        });
                  });
        }

        if (in_array($status, ['pending', 'paid'], true)) {
            $query->where('status', $status);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();
        $counts = [
            'all' => Invoice::count(),
            'pending' => Invoice::where('status', 'pending')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
        ];

        return view('pages.invoices.index', compact('invoices', 'counts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => $validated['status']]);

        return redirect()->route('invoices.index', request()->only('status', 'search'))
            ->with('success', 'Statut de facture mis a jour.');
    }

    public function downloadPdf($id)
    {
        $invoice = Invoice::with(['contract.client.company'])->findOrFail($id);

        if (auth()->check() && auth()->user()->isClient() && $invoice->contract->client_id !== auth()->user()->client_id) {
            abort(403, 'Acces interdit.');
        }
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Send invoice PDF by email to the client.
     */
    public function sendEmail($id)
    {
        $invoice = Invoice::with(['contract.client.company'])->findOrFail($id);
        $clientEmail = $invoice->contract->client->email;

        if (!$clientEmail) {
            return redirect()->back()->with('error', 'Ce client n\'a pas d\'adresse email enregistrée.');
        }

        try {
            Mail::to($clientEmail)->send(new InvoiceMail($invoice));
            return redirect()->back()->with('success', 'La facture a été envoyée par email à ' . $clientEmail . ' avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }
    
    public function contractPdf($id)
    {
        $contract = \App\Models\Contract::with(['client.company'])->findOrFail($id);

        if (auth()->check() && auth()->user()->isClient() && $contract->client_id !== auth()->user()->client_id) {
            abort(403, 'Acces interdit.');
        }
        
        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        
        return $pdf->download('Contract_' . $contract->client->last_name . '.pdf');
    }

    public function contractWord($id)
    {
        $contract = \App\Models\Contract::with(['client.company'])->findOrFail($id);

        if (auth()->check() && auth()->user()->isClient() && $contract->client_id !== auth()->user()->client_id) {
            abort(403, 'Acces interdit.');
        }

        $fileName = 'Contract_' . $contract->client->last_name . '.doc';

        return response()
            ->view('word.contract', compact('contract'))
            ->header('Content-Type', 'application/msword; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
