@extends('layouts.app')

@section('title', 'Espace Client')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Bienvenue {{ $client->first_name }} {{ $client->last_name }}</h5>
                <p class="mb-0 text-muted">Vous pouvez soumettre une demande de contrat et suivre vos contrats/factures.</p>
            </div>
            <a href="{{ route('client.contract.create') }}" class="btn btn-primary">Nouvelle demande de contrat</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Mes contrats</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Type</th><th>Periode</th><th>Statut</th><th>Telechargement</th></tr></thead>
                        <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>{{ $contract->type }}</td>
                                <td>{{ optional($contract->start_date)->format('d/m/Y') }} - {{ optional($contract->end_date)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge {{ $contract->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $contract->status === 'active' ? 'Actif' : 'En attente' }}
                                    </span>
                                </td>
                                <td>
                                    @if($contract->status === 'active')
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('client.contracts.pdf', $contract->id) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                                            <a href="{{ route('client.contracts.word', $contract->id) }}" class="btn btn-sm btn-outline-dark">Word</a>
                                        </div>
                                    @else
                                        <span class="text-muted small">Disponible apres approbation</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Aucun contrat</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Mes factures</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Numero</th><th>Montant</th><th>Action</th></tr></thead>
                        <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} MAD</td>
                                <td><a href="{{ route('client.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-primary">PDF</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucune facture</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
