<nav id="sidebar">
    <a href="<?php echo e(auth()->user()?->isAdmin() ? route('admin.dashboard') : route('client.dashboard')); ?>" class="sidebar-brand d-flex align-items-center">
        <img src="<?php echo e(asset('uis-logo.png')); ?>" alt="UIS" style="height: 32px; width: auto; margin-right: 10px;">
        <span style="font-size: 0.9rem; line-height: 1.2;">Universal Invest Strategy</span>
    </a>
    <ul class="sidebar-nav">
        <?php if(auth()->user()?->isAdmin()): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('admin/dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="fa-solid fa-house"></i> Tableau de bord
                    <?php if(($expiringContractsCount ?? 0) > 0): ?>
                        <span class="badge bg-danger ms-auto"><?php echo e($expiringContractsCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Contrats & Clients</div>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('clients') || request()->is('clients/*') ? 'active' : ''); ?>" href="<?php echo e(route('clients.index')); ?>">
                    <i class="fa-solid fa-users"></i> Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('contracts') || request()->is('contracts/*') ? 'active' : ''); ?>" href="<?php echo e(route('contracts.index')); ?>">
                    <i class="fa-solid fa-file-contract"></i> Voir les Contrats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('contracts/create') ? 'active' : ''); ?>" href="<?php echo e(route('contracts.create')); ?>">
                    <i class="fa-solid fa-file-circle-plus"></i> Nouveau Contrat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('contracts/pending') ? 'active' : ''); ?>" href="<?php echo e(route('contracts.pending')); ?>">
                    <i class="fa-solid fa-hourglass-half"></i> Demandes en attente
                    <?php if(($pendingContractsCount ?? 0) > 0): ?>
                        <span class="badge bg-primary ms-auto"><?php echo e($pendingContractsCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Facturation & Finance</div>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('invoices') ? 'active' : ''); ?>" href="<?php echo e(route('invoices.index')); ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Factures
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('accounting') ? 'active' : ''); ?>" href="<?php echo e(route('accounting.index')); ?>">
                    <i class="fa-solid fa-book-journal-whills"></i> Journal des Ventes
                </a>
            </li>
            <li class="nav-item mt-3">
                <div class="text-uppercase text-secondary small px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Outils</div>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('export') ? 'active' : ''); ?>" href="<?php echo e(route('export.index')); ?>">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Exportation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('import') ? 'active' : ''); ?>" href="<?php echo e(route('import.index')); ?>">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Importation
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('client/dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('client.dashboard')); ?>">
                    <i class="fa-solid fa-house"></i> Mon espace
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('client/demande-contrat') ? 'active' : ''); ?>" href="<?php echo e(route('client.contract.create')); ?>">
                    <i class="fa-solid fa-file-circle-plus"></i> Demande de contrat
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>