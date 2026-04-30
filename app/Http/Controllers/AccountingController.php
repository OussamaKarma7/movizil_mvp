<?php

namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingEntry::with('invoice.contract.client');

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

        $entries = $query->orderBy('date', 'desc')->paginate(20);

        return view('pages.accounting.index', compact('entries'));
    }

}
