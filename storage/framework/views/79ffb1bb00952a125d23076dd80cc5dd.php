<?php $__env->startSection('title', 'Aperçu du Tableau de Bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- KPI Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Clients Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid var(--primary-color) !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Total Clients</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e($stats['clients'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Total Contracts Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Contrats Actifs</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e($stats['active_contracts'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Factures / Attente</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e($stats['pending_invoices'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #ef4444 !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Revenu (<?php echo e(now()->translatedFormat('M')); ?>)</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e(number_format($stats['monthly_revenue'] ?? 0, 0)); ?> <small class="text-secondary">DH</small></div>
                </div>
            </div>
        </div>

        <!-- Total Invoiced Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Ventes Totales</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e(number_format($stats['total_invoiced'] ?? 0, 0)); ?> <small class="text-secondary">DH</small></div>
                </div>
            </div>
        </div>

        <!-- Outstanding Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #06b6d4 !important;">
                <div class="card-body p-3 text-center">
                    <div class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Créances Clients</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?php echo e(number_format($stats['outstanding'] ?? 0, 0)); ?> <small class="text-secondary">DH</small></div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($expiringContracts ?? []) > 0): ?>
        <!-- Dynamic Expiry Alerts -->
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" style="border-left: 4px solid #ef4444 !important;">
            <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                <i class="fa-solid fa-bell text-danger"></i>
            </div>
            <div>
                <h6 class="alert-heading fw-bold mb-0 text-danger">Attention : <?php echo e(count($expiringContracts)); ?> contrat(s) arrivent à échéance !</h6>
                <p class="small mb-0 opacity-75">Consultez la liste ci-dessous pour lancer les renouvellements ou avertir les clients.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-danger"><i class="fa-solid fa-clock-rotate-left me-2"></i>Échéances Prochaines (15 jours)</h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th class="ps-4">Client</th>
                            <th>Expire le</th>
                            <th>Status / Alerte</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $expiringContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $daysLeft = now()->diffInDays($contract->end_date, false);
                                $badgeClass = ($daysLeft <= 7) ? 'bg-danger' : 'bg-warning text-dark';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></div>
                                    <div class="small text-secondary"><?php echo e($contract->client->company->company_name ?? '-'); ?></div>
                                </td>
                                <td><?php echo e(optional($contract->end_date)->format('d/m/Y')); ?></td>
                                <td>
                                    <span class="badge <?php echo e($badgeClass); ?>">
                                        <?php if($daysLeft < 0): ?>
                                            Expiré il y a <?php echo e(abs($daysLeft)); ?> j
                                        <?php elseif($daysLeft == 0): ?>
                                            Expire AUJOURD'HUI
                                        <?php else: ?>
                                            Expire dans <?php echo e($daysLeft); ?> jours
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo e(route('contracts.renew', $contract->id)); ?>" class="btn btn-sm btn-primary">Renouveler</a>
                                        <form method="POST" action="<?php echo e(route('contracts.notifyExpiry', $contract->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-warning text-dark"><i class="fa-solid fa-paper-plane me-1"></i>Avertir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-3 text-success"></i>
            <span class="fw-medium">Aucun contrat n'arrive à échéance dans les 15 prochains jours. Le système est à jour !</span>
        </div>
        <?php endif; ?>

            <!-- Sage Sync Global Card -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <form action="<?php echo e(route('export.direct.sync')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="card h-100 border-0 shadow-sm w-100 text-center p-0 overflow-hidden bg-success bg-opacity-10" style="border-top: 4px solid #10b981 !important;">
                    <div class="card-body p-3">
                        <div class="text-success fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Synchronisation Sage</div>
                        <div class="h6 mb-0 fw-bold text-success"><i class="fa-solid fa-sync me-1"></i> TOUT EXPORTER</div>
                    </div>
                </button>
            </form>
        </div>
    </div>
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark">Demandes de contrat en attente</h6>
            <a href="<?php echo e(route('contracts.pending')); ?>" class="btn btn-sm btn-outline-primary">Voir toutes les demandes</a>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Entreprise</th>
                        <th>Contrat</th>
                        <th>Date debut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></td>
                            <td><?php echo e($contract->client->company->company_name ?? '-'); ?></td>
                            <td><?php echo e($contract->type); ?></td>
                            <td><?php echo e(optional($contract->start_date)->format('d/m/Y')); ?></td>
                            <td class="d-flex gap-2">
                                <a href="<?php echo e(route('contracts.show', $contract->id)); ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                                <form method="POST" action="<?php echo e(route('contracts.approve', $contract->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-success" type="submit">Approuver</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucune demande en attente pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="m-0 fw-bold text-dark">Aperçu des Revenus</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px; width:100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="m-0 fw-bold text-dark">Durée des Contrats</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center pb-4">
                    <div style="height: 250px; width:100%;">
                        <canvas id="contractChart"></canvas>
                    </div>
                    <div class="mt-4 w-100">
                        <?php
                            $total = array_sum($chartData['durations']['data']);
                            $colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                        ?>
                        <?php $__currentLoopData = $chartData['durations']['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary fs-sm">
                                    <i class="fa-solid fa-circle me-2" style="color: <?php echo e($colors[$index % count($colors)]); ?>; font-size: 8px;"></i>
                                    <?php echo e($label); ?> <?php echo e($label > 1 ? 'Mois' : 'Mois'); ?>

                                </span>
                                <span class="fw-bold text-dark"><?php echo e($total > 0 ? round(($chartData['durations']['data'][$index] / $total) * 100) : 0); ?>%</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($total == 0): ?>
                            <div class="text-center text-muted small">Aucune donnée de contrat</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Revenue Area Chart
    const ctx = document.getElementById('revenueChart');
    if(ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartData['revenue']['labels']); ?>,
                datasets: [{
                    label: "Revenu (MAD)",
                    tension: 0.4,
                    backgroundColor: "rgba(79, 70, 229, 0.1)",
                    borderColor: "rgba(79, 70, 229, 1)",
                    pointRadius: 4,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "rgba(79, 70, 229, 1)",
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "rgba(79, 70, 229, 1)",
                    pointHoverBorderColor: "#fff",
                    borderWidth: 2,
                    data: <?php echo json_encode($chartData['revenue']['data']); ?>,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, family: "'Inter', sans-serif" },
                        displayColors: false,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: "'Inter', sans-serif" } }
                    },
                    y: {
                        grid: { color: "#f1f5f9", drawBorder: false },
                        ticks: { 
                            color: '#94a3b8', 
                            font: { family: "'Inter', sans-serif" },
                            callback: function(value) {
                                return value >= 1000 ? (value / 1000).toFixed(1) + 'k' : value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Contract Doughnut Chart
    const ctx2 = document.getElementById('contractChart');
    if(ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_map(fn($d) => $d . ' Mois', $chartData['durations']['labels'])); ?>,
                datasets: [{
                    data: <?php echo json_encode($chartData['durations']['data']); ?>,
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14 },
                        cornerRadius: 8
                    }
                },
            },
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>