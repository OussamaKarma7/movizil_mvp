<?php

namespace App\Services;

use App\Models\AccountingEntry;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Create standard accounting entries for an invoice.
     * Includes Client (Debit), Sales (Credit), and VAT (Credit).
     */
    public function generateInvoiceEntries(Invoice $invoice, string $labelPrefix = 'DOM'): void
    {
        $invoice->load(['contract.client']);
        $client = $invoice->contract->client;
        $price = $invoice->amount;
        
        $vatRate = config('pricing.contract_creation.standard_vat_rate', 0.20);
        $vatAmount = $price * $vatRate;
        $totalTTC = $price + $vatAmount;

        $tierId = $client->sage_custom_id ?: 'C' . str_pad($client->id, 3, '0', STR_PAD_LEFT);
        $clientName = mb_strtoupper($client->last_name);
        $invoiceNumber = $invoice->invoice_number;

        DB::transaction(function () use ($invoice, $tierId, $clientName, $invoiceNumber, $totalTTC, $price, $vatAmount, $labelPrefix) {
            // 1. Client Account (Debit TTC)
            AccountingEntry::create([
                'invoice_id' => $invoice->id,
                'account_number' => config('pricing.accounting.default_client_account', '34210000'),
                'third_party_account' => $tierId,
                'label' => "$labelPrefix $invoiceNumber $clientName",
                'debit' => $totalTTC,
                'credit' => 0,
                'date' => now()->toDateString(),
            ]);

            // 2. Sales Account (Credit HT)
            AccountingEntry::create([
                'invoice_id' => $invoice->id,
                'account_number' => config('pricing.accounting.default_sales_account', '71210000'),
                'label' => "$labelPrefix $invoiceNumber $clientName",
                'debit' => 0,
                'credit' => $price,
                'date' => now()->toDateString(),
            ]);

            // 3. VAT Account (Credit VAT)
            AccountingEntry::create([
                'invoice_id' => $invoice->id,
                'account_number' => config('pricing.accounting.default_vat_account', '44550000'),
                'label' => "TVA 20 $invoiceNumber $clientName",
                'debit' => 0,
                'credit' => $vatAmount,
                'date' => now()->toDateString(),
            ]);
        });
    }
}
