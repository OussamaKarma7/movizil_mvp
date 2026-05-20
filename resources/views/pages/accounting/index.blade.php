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
                        <div class="col-auto ms-2">
                            <form action="{{ route('export.direct.sync') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-3 rounded shadow-sm">
                                    <i class="fa-solid fa-rotate me-1"></i> Sinc. vers Sage 
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="bg-light text-nowrap">
                        <tr>
                            <th class="ps-3">Jour</th>
                            <th>N° pièce</th>
                            <th>N° facture</th>
                            <th>Référence</th>
                            <th>N° compte g</th>
                            <th>N° compte ti</th>
                            <th>Libellé écriture</th>
                            <th>Date échéance</th>
                            <th>Pos.</th>
                            <th class="text-end">Débit</th>
                            <th class="text-end pe-3">Crédit</th>
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
                        <tr class="text-nowrap">
                            <td class="ps-3">{{ $entry->jour }}</td>
                            <td class="text-secondary">{{ $entry->piece }}</td>
                            <td class="fw-bold">{{ $entry->invoice?->invoice_number ?? '-' }}</td>
                            <td>{{ $entry->reference }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $entry->compte_g }}</span></td>
                            <td>{{ $entry->compte_t }}</td>
                            <td class="text-truncate" style="max-width: 250px;">{{ $entry->label }}</td>
                            <td>{{ $entry->echeance }}</td>
                            <td class="text-center text-muted">{{ $entry->position }}</td>
                            <td class="text-end fw-bold {{ $entry->debit > 0 ? 'text-dark' : 'text-muted opacity-25' }}">
                                {{ $entry->debit > 0 ? number_format($entry->debit, 2, ',', ' ') : '0,00' }}
                            </td>
                            <td class="text-end pe-3 fw-bold {{ $entry->credit > 0 ? 'text-dark' : 'text-muted opacity-25' }}">
                                {{ $entry->credit > 0 ? number_format($entry->credit, 2, ',', ' ') : '0,00' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="text-center py-4 text-muted">Aucune écriture trouvée.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="9" class="text-end px-4 py-2">Totaux :</td>
                            <td class="text-end text-primary">{{ number_format($totalDebit, 2, ',', ' ') }}</td>
                            <td class="text-end pe-3 text-primary">{{ number_format($totalCredit, 2, ',', ' ') }}</td>
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
