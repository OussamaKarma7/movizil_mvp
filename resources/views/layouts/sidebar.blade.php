<nav id="sidebar">
    <a href="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}" class="sidebar-brand d-flex align-items-center">
        <img src="{{ asset('uis-logo.png') }}" alt="UIS" style="height: 32px; width: auto; margin-right: 10px;">
        <span style="font-size: 0.9rem; line-height: 1.2;">Universal Invest Strategy</span>
    </a>
    <ul class="sidebar-nav">
        @if(auth()->user()?->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-house"></i> Tableau de bord
                    @if(($expiringContractsCount ?? 0) > 0)
                        <span class="badge bg-danger ms-auto">{{ $expiringContractsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Contrats & Clients</div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('clients') || request()->is('clients/*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                    <i class="fa-solid fa-users"></i> Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('contracts') || request()->is('contracts/*') ? 'active' : '' }}" href="{{ route('contracts.index') }}">
                    <i class="fa-solid fa-file-contract"></i> Voir les Contrats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('contracts/create') ? 'active' : '' }}" href="{{ route('contracts.create') }}">
                    <i class="fa-solid fa-file-circle-plus"></i> Nouveau Contrat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('contracts/pending') ? 'active' : '' }}" href="{{ route('contracts.pending') }}">
                    <i class="fa-solid fa-hourglass-half"></i> Demandes en attente
                    @if(($pendingContractsCount ?? 0) > 0)
                        <span class="badge bg-primary ms-auto">{{ $pendingContractsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Facturation & Finance</div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('invoices') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Factures
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('accounting') ? 'active' : '' }}" href="{{ route('accounting.index') }}">
                    <i class="fa-solid fa-book-journal-whills"></i> Journal des Ventes
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Outils</div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('export') ? 'active' : '' }}" href="{{ route('export.index') }}">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Exportation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('import') ? 'active' : '' }}" href="{{ route('import.index') }}">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Importation
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ request()->is('client/dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">
                    <i class="fa-solid fa-house"></i> Mon espace
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('client/demande-contrat') ? 'active' : '' }}" href="{{ route('client.contract.create') }}">
                    <i class="fa-solid fa-file-circle-plus"></i> Demande de contrat
                </a>
            </li>
        @endif
    </ul>
</nav>
