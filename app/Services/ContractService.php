<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractService
{
    protected $accountingService;
    protected $sageService;

    public function __construct(AccountingService $accountingService, SageService $sageService)
    {
        $this->accountingService = $accountingService;
        $this->sageService = $sageService;
    }

    /**
     * Calculate price and duration for a contract.
     */
    public function calculatePricing(Carbon $start, Carbon $end, bool $isRenewal = false): array
    {
        $duration = $start->diffInMonths($end) ?: 1;
        
        if ($isRenewal) {
            $pricePerMonth = config('pricing.contract_renewal.monthly_price', 165);
            $totalPrice = $duration * $pricePerMonth;
        } else {
            $years = ceil($duration / 12);
            $totalPrice = $years * config('pricing.contract_creation.base_price_per_year', 800);
        }

        return [
            'duration' => $duration,
            'price' => $totalPrice
        ];
    }

    /**
     * Generate a unique invoice number atomically.
     */
    public function generateInvoiceNumber(): string
    {
        return DB::transaction(function () {
            $count = Invoice::lockForUpdate()->count();
            return 'INV-' . date('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Finalize and Activate a contract (generate invoice & entries).
     */
    public function activateContract(Contract $contract): Invoice
    {
        return DB::transaction(function () use ($contract) {
            $contract->update(['status' => 'active']);

            $invoice = Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'amount' => $contract->price,
                'status' => 'pending',
                'date' => now()->toDateString(),
            ]);

            $this->accountingService->generateInvoiceEntries($invoice, 'DOM');

            // Automatic Sync to Local Sage Folder
            $this->sageService->syncNow();

            return $invoice;
        });
    }
}
