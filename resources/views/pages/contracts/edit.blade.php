@extends('layouts.app')

@section('content')
<div class="row items-center mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">Modifier le Contrat #{{ $contract->id }}</h1>
        <p class="text-muted">Client: {{ $contract->client->first_name }} {{ $contract->client->last_name }} ({{ $contract->client->company->company_name ?? 'Individuel' }})</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('contracts.update', $contract->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Type de Contrat</label>
                            <input type="text" name="type" class="form-control" value="{{ old('type', $contract->type) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de Début</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de Fin</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prix (MAD)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $contract->price) }}" required>
                                <span class="input-group-text">DH</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Statut</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $contract->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="active" {{ $contract->status === 'active' ? 'selected' : '' }}>Actif</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Référence (REF)</label>
                            <input type="text" name="ref" class="form-control" value="{{ old('ref', $contract->ref) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date Création</label>
                            <input type="date" name="date_creation" class="form-control" value="{{ old('date_creation', $contract->date_creation ? $contract->date_creation->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Interlocuteur</label>
                            <input type="text" name="interlocuteur" class="form-control" value="{{ old('interlocuteur', $contract->interlocuteur) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Montant HT (Laissez vide pour calcul auto)</label>
                            <input type="number" step="0.01" name="montant_ht" class="form-control" value="{{ old('montant_ht', $contract->montant_ht) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Remarque</label>
                            <textarea name="remarque" class="form-control" rows="2">{{ old('remarque', $contract->remarque) }}</textarea>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fa-solid fa-save"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-link text-secondary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-4">
                <h5 class="fw-bold text-danger mb-3">Zone de Danger</h5>
                <p class="small text-muted mb-4">La suppression d'un contrat supprimera également la facture associée et toutes les écritures comptables liées dans le journal des ventes.</p>
                
                <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat et TOUS ses documents associés ? Cette action est irréversible.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fa-solid fa-trash"></i> Supprimer le Contrat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
