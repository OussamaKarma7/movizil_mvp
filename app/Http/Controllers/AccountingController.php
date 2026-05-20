<?php

namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingEntry::with('invoice.contract.client.company');

        // Optional filtering by account
        if ($request->has('account_number') && !empty($request->account_number)) {
            $query->where('account_number', $request->account_number);
        }

        // Optional filtering by month
        if ($request->has('month') && !empty($request->month)) {
            $month = date('m', strtotime($request->month));
            $year = date('Y', strtotime($request->month));
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }

        $entries = $query->orderBy('date', 'desc')->paginate(50);

        // Add display-specific fields for Sage UI alignment
        $entries->getCollection()->transform(function($entry, $key) {
            $entry->jour = $entry->date->format('d');
            $entry->piece = str_pad($entry->invoice->invoice_number ?? 'FAC', 10, '0', STR_PAD_LEFT);
            $entry->reference = substr($entry->invoice->contract->client->company->company_name ?? $entry->invoice->contract->client->last_name, 0, 15);
            $entry->compte_g = str_pad($entry->account_number, 8, '0', STR_PAD_RIGHT);
            $entry->compte_t = str_pad($entry->third_party_account ?? '', 8, ' ', STR_PAD_RIGHT);
            $entry->echeance = $entry->date->format('d/m/y');
            $entry->position = ($entry->debit > 0) ? 1 : 2;
            return $entry;
        });

        return view('pages.accounting.index', compact('entries'));
    }

}
