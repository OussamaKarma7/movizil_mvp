@extends('layouts.app')

@section('title', 'Detail Client')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Informations personnelles</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nom:</strong> {{ $client->first_name }} {{ $client->last_name }}</p>
                    <p class="mb-2"><strong>Date naissance:</strong> {{ optional($client->birth_date)->format('d/m/Y') ?? '-' }}</p>
                    <p class="mb-2"><strong>CIN:</strong> {{ $client->cin }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $client->email }}</p>
                    <p class="mb-2"><strong>Telephone:</strong> {{ $client->phone }}</p>
                    <p class="mb-0"><strong>Adresse:</strong> {{ $client->address }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white fw-bold">Configuration Sage 100</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clients.update', $client->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ID Compte Tiers Sage (ex: 464)</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="sage_custom_id" value="{{ $client->sage_custom_id }}" placeholder="Par défaut: C{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}">
                                <button class="btn btn-sm btn-primary" type="submit">Enregistrer</button>
                            </div>
                            <div class="form-text mt-1 small" style="font-size: 0.7rem;">Cet ID sera utilisé dans la colonne "Compte Tiers" lors de l'exportation journalière.</div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white fw-bold">Informations entreprise</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nom entreprise:</strong> {{ $client->company->company_name ?? '-' }}</p>
                    <p class="mb-2"><strong>RC:</strong> {{ $client->company->rc ?? '-' }}</p>
                    <p class="mb-2"><strong>RCE:</strong> {{ $client->company->rce ?? '-' }}</p>
                    <p class="mb-2"><strong>ICE:</strong> {{ $client->company->ice ?? '-' }}</p>
                    <p class="mb-2"><strong>IF:</strong> {{ $client->company->if ?? '-' }}</p>
                    <p class="mb-2"><strong>Forme juridique:</strong> {{ $client->company->legal_form ?? '-' }}</p>
                    <p class="mb-2"><strong>Activite:</strong> {{ $client->company->activity ?? '-' }}</p>
                    <p class="mb-0"><strong>Siege social:</strong> {{ $client->company->headquarters_address ?? '-' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white fw-bold">Pieces jointes a l'inscription</div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @if($client->registration_cin_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($client->registration_cin_path))
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('clients.attachment', ['id' => $client->id, 'type' => 'cin']) }}">CIN inscription</a>
                        @endif
                        @if($client->registration_company_doc_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($client->registration_company_doc_path))
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('clients.attachment', ['id' => $client->id, 'type' => 'company_doc']) }}">Document entreprise inscription</a>
                        @endif
                        @if(
                            (!$client->registration_cin_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($client->registration_cin_path))
                            &&
                            (!$client->registration_company_doc_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($client->registration_company_doc_path))
                        )
                            <span class="text-muted small">Aucune piece importee a l'inscription.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Contrats et pieces jointes</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Contrat</th>
                                <th>Periode</th>
                                <th>Statut</th>
                                <th>Pieces jointes</th>
                                <th>Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($client->contracts && count($client->contracts) > 0)
                                @foreach($client->contracts as $contract)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $contract->type }}</div>
                                            <small class="text-muted">Ref #{{ $contract->id }}</small>
                                        </td>
                                        <td>{{ optional($contract->start_date)->format('d/m/Y') }} - {{ optional($contract->end_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $contract->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $contract->status === 'active' ? 'Actif' : 'En attente' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($contract->cin_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($contract->cin_path))
                                                    <a href="{{ route('contracts.attachment', ['id' => $contract->id, 'type' => 'cin']) }}" class="btn btn-sm btn-outline-secondary">CIN</a>
                                                @endif
                                                @if($contract->certificat_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($contract->certificat_path))
                                                    <a href="{{ route('contracts.attachment', ['id' => $contract->id, 'type' => 'certificat']) }}" class="btn btn-sm btn-outline-secondary">Certificat</a>
                                                @endif
                                                @if(
                                                    (!$contract->cin_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($contract->cin_path))
                                                    &&
                                                    (!$contract->certificat_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($contract->certificat_path))
                                                )
                                                    <span class="text-muted small">Aucune piece</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                                <a href="{{ route('contracts.pdf', $contract->id) }}" class="btn btn-sm btn-outline-primary">Contrat PDF</a>
                                                <a href="{{ route('contracts.word', $contract->id) }}" class="btn btn-sm btn-outline-dark">Contrat Word</a>
                                                @if($contract->invoice)
                                                    <a href="{{ route('invoices.pdf', $contract->invoice->id) }}" class="btn btn-sm btn-outline-success">Facture PDF</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5" class="text-center text-muted py-4">Aucun contrat pour ce client.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
