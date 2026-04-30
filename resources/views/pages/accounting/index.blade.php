@extends('layouts.app')

@section('title', 'Journal des Ventes')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0">
        <!-- Header & Filters -->
        <div class="card-header bg-white border-bottom p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <h5 class="mb-0 fw-bold">Écritures Comptables</h5>
                    <small class="text-secondary">Journal des Ventes</small>
                </div>
                <div class="col-md-9 text-md-end">
                    <form action="{{ route('accounting.index') }}" method="GET" class="row g-2 justify-content-md-end">
                        <div class="col-auto">
                            <select name="account_number" class="form-select form-select-sm border-light bg-light" onchange="this.form.submit()">
                                <option value="">Tous les Comptes</option>
                                <option value="3421" {{ request('account_number') == '3421' ? 'selected' : '' }}>3421 - Clients</option>
                                <option value="7121" {{ request('account_number') == '7121' ? 'selected' : '' }}>7121 - Ventes de services</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="month" name="month" class="form-control form-control-sm border-light bg-light" value="{{ request('month') }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary px-3 rounded"><i class="fa-solid fa-filter me-1"></i> Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Date</th>
                            <th>Réf. Pièce</th>
                            <th>Compte</th>
                            <th>Libellé</th>
                            <th class="text-end">Débit (MAD)</th>
                            <th class="text-end pe-4">Crédit (MAD)</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @php
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp
                        @forelse($entries as $entry)
                        @php
                            $totalDebit += $entry->debit;
                            $totalCredit += $entry->credit;
                        @endphp
                        <tr>
                            <td class="px-4 fw-medium text-dark">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                            <td>{{ $entry->invoice?->invoice_number ?? '-' }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $entry->account_number }}</span></td>
                            <td class="fw-medium text-dark">{{ $entry->label }}</td>
                            <td class="text-end fw-bold {{ $entry->debit > 0 ? 'text-success' : 'text-secondary' }}">
                                {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}
                            </td>
                            <td class="text-end pe-4 fw-bold {{ $entry->credit > 0 ? 'text-danger' : 'text-secondary' }}">
                                {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucune écriture trouvée.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end px-4 py-3">Totaux de la période :</td>
                            <td class="text-end text-primary fs-5">{{ number_format($totalDebit, 2) }}</td>
                            <td class="text-end pe-4 text-primary fs-5">{{ number_format($totalCredit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            {{ $entries->links() }}
        </div>
    </div>
</div>
@endsection
