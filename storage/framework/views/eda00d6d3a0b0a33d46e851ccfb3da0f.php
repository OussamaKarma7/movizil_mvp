<?php $__env->startSection('title', 'Contracts Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-file-contract text-primary me-2"></i> Tous les Contrats</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-sm <?php echo e(request('status') ? 'btn-light border' : 'btn-dark'); ?>">
                            Tous (<?php echo e($counts['all'] ?? 0); ?>)
                        </a>
                        <a href="<?php echo e(route('contracts.index', ['status' => 'pending'])); ?>" class="btn btn-sm <?php echo e(request('status') === 'pending' || request()->is('contracts/pending') ? 'btn-warning' : 'btn-light border'); ?>">
                            En attente (<?php echo e($counts['pending'] ?? 0); ?>)
                        </a>
                        <a href="<?php echo e(route('contracts.index', ['status' => 'active'])); ?>" class="btn btn-sm <?php echo e(request('status') === 'active' ? 'btn-success' : 'btn-light border'); ?>">
                            Actifs (<?php echo e($counts['active'] ?? 0); ?>)
                        </a>
                        <a href="<?php echo e(route('contracts.create')); ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Nouveau Contrat</a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover table-sm align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr class="small text-uppercase">
                                <th>REF</th>
                                <th>Nom Société</th>
                                <th>Date Création</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Statut</th>
                                <th>Droit Cons. (J)</th>
                                <th>Réel/J</th>
                                <th>Réel/Mois</th>
                                <th>Valeur (HT)</th>
                                <th>Surplus/J</th>
                                <th>Surplus/Mois</th>
                                <th>Échues</th>
                                <th>Interlocuteur</th>
                                <th>Remarque</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="small">
                                <td class="fw-bold"><?php echo e($contract->ref ?? '-'); ?></td>
                                <td><?php echo e($contract->client?->company?->company_name ?? '-'); ?></td>
                                <td><?php echo e($contract->date_creation ? $contract->date_creation->format('d/m/Y') : ($contract->created_at ? $contract->created_at->format('d/m/Y') : '-')); ?></td>
                                <td><?php echo e($contract->start_date ? $contract->start_date->format('d/m/Y') : '-'); ?></td>
                                <td><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : '-'); ?></td>
                                <td>
                                    <span class="badge <?php echo e($contract->status == 'active' ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                        <?php echo e(ucfirst($contract->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($contract->droit_consomme); ?></td>
                                <td><?php echo e($contract->reel_consomme_j); ?></td>
                                <td><?php echo e(number_format($contract->reel_consomme_mois, 2)); ?></td>
                                <td class="fw-bold"><?php echo e(number_format($contract->calculated_valeur_ht, 2)); ?> DH</td>
                                <td class="text-<?php echo e($contract->surplus_jour > 0 ? 'danger' : 'success'); ?>">
                                    <?php echo e($contract->surplus_jour); ?>

                                </td>
                                <td class="text-<?php echo e($contract->surplus_mois > 0 ? 'danger' : 'success'); ?>">
                                    <?php echo e(number_format($contract->surplus_mois, 2)); ?>

                                </td>
                                <td>
                                    <span class="badge <?php echo e($contract->echue_status == 'Échue' ? 'bg-danger' : 'bg-success'); ?>">
                                        <?php echo e($contract->echue_status); ?>

                                    </span>
                                </td>
                                <td><?php echo e($contract->interlocuteur ?? '-'); ?></td>
                                <td class="text-truncate" style="max-width: 150px;" title="<?php echo e($contract->remarque); ?>"><?php echo e($contract->remarque ?? '-'); ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="<?php echo e(route('contracts.show', $contract->id)); ?>" class="btn btn-xs btn-light border" title="Voir"><i class="fa-solid fa-eye text-primary"></i></a>
                                        <a href="<?php echo e(route('contracts.edit', $contract->id)); ?>" class="btn btn-xs btn-light border" title="Modifier"><i class="fa-solid fa-pen-to-square text-secondary"></i></a>
                                        
                                        <?php if($contract->status == 'pending'): ?>
                                        <form action="<?php echo e(route('contracts.approve', $contract->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-xs btn-success" title="Approuver"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('contracts.renew', $contract->id)); ?>" class="btn btn-xs btn-info text-white" title="Renouveler"><i class="fa-solid fa-sync"></i></a>
                                        <?php endif; ?>

                                        <form action="<?php echo e(route('contracts.destroy', $contract->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce contrat ?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-xs btn-light border text-danger" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="16" class="text-center py-4 text-muted">Aucun contrat trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    <?php echo e($contracts->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/pages/contracts/index.blade.php ENDPATH**/ ?>