@extends('layouts.app')
@section('title', 'Contracts Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-file-contract text-primary me-2"></i> Tous les Contrats</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('contracts.index') }}" class="btn btn-sm {{ request('status') ? 'btn-light border' : 'btn-dark' }}">
                            Tous ({{ $counts['all'] ?? 0 }})
                        </a>
                        <a href="{{ route('contracts.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' || request()->is('contracts/pending') ? 'btn-warning' : 'btn-light border' }}">
                            En attente ({{ $counts['pending'] ?? 0 }})
                        </a>
                        <a href="{{ route('contracts.index', ['status' => 'active']) }}" class="btn btn-sm {{ request('status') === 'active' ? 'btn-success' : 'btn-light border' }}">
                            Actifs ({{ $counts['active'] ?? 0 }})
                        </a>
                        <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Nouveau Contrat</a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom du Client</th>
                                <th>Entreprise</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contracts as $contract)
                            <tr>
                                <td>{{ $contract->client?->first_name }} {{ $contract->client?->last_name }}</td>
                                <td>{{ $contract->client?->company?->company_name ?? '-' }}</td>
                                <td>
                                    {{ $contract->type }}
                                    @if($contract->is_renewal)
                                        <span class="badge bg-info text-white" style="font-size: 0.6rem;">Renouvellement</span>
                                    @endif
                                </td>
                                <td>{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : '' }}</td>
                                <td>
                                    @if($contract->status == 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                    @else
                                    <span class="badge bg-success">Actif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-light border" title="Voir"><i class="fa-solid fa-eye text-primary"></i></a>
                                        <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-light border" title="Modifier"><i class="fa-solid fa-pen-to-square text-secondary"></i></a>
                                        
                                        @if($contract->status == 'pending')
                                        <form action="{{ route('contracts.approve', $contract->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Approuver"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                        @else
                                        <a href="{{ route('contracts.renew', $contract->id) }}" class="btn btn-sm btn-info text-white" title="Renouveler"><i class="fa-solid fa-sync"></i></a>
                                        @endif

                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce contrat et ses documents ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Aucun contrat trouvé.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
