<?php $__env->startSection('title', 'Journal des Ventes'); ?>

<?php $__env->startSection('content'); ?>
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
                    <form action="<?php echo e(route('accounting.index')); ?>" method="GET" class="row g-2 justify-content-md-end">
                        <div class="col-auto">
                            <select name="account_number" class="form-select form-select-sm border-light bg-light" onchange="this.form.submit()">
                                <option value="">Tous les Comptes</option>
                                <option value="3421" <?php echo e(request('account_number') == '3421' ? 'selected' : ''); ?>>3421 - Clients</option>
                                <option value="7121" <?php echo e(request('account_number') == '7121' ? 'selected' : ''); ?>>7121 - Ventes de services</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="month" name="month" class="form-control form-control-sm border-light bg-light" value="<?php echo e(request('month')); ?>" onchange="this.form.submit()">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary px-3 rounded"><i class="fa-solid fa-filter me-1"></i> Filtrer</button>
                        </div>
                        <div class="col-auto ms-2">
                            <form action="<?php echo e(route('export.direct.sync')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
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
                        <?php
                            $totalDebit = 0;
                            $totalCredit = 0;
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $totalDebit += $entry->debit;
                            $totalCredit += $entry->credit;
                        ?>
                        <tr class="text-nowrap">
                            <td class="ps-3"><?php echo e($entry->jour); ?></td>
                            <td class="text-secondary"><?php echo e($entry->piece); ?></td>
                            <td class="fw-bold"><?php echo e($entry->invoice?->invoice_number ?? '-'); ?></td>
                            <td><?php echo e($entry->reference); ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border"><?php echo e($entry->compte_g); ?></span></td>
                            <td><?php echo e($entry->compte_t); ?></td>
                            <td class="text-truncate" style="max-width: 250px;"><?php echo e($entry->label); ?></td>
                            <td><?php echo e($entry->echeance); ?></td>
                            <td class="text-center text-muted"><?php echo e($entry->position); ?></td>
                            <td class="text-end fw-bold <?php echo e($entry->debit > 0 ? 'text-dark' : 'text-muted opacity-25'); ?>">
                                <?php echo e($entry->debit > 0 ? number_format($entry->debit, 2, ',', ' ') : '0,00'); ?>

                            </td>
                            <td class="text-end pe-3 fw-bold <?php echo e($entry->credit > 0 ? 'text-dark' : 'text-muted opacity-25'); ?>">
                                <?php echo e($entry->credit > 0 ? number_format($entry->credit, 2, ',', ' ') : '0,00'); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="11" class="text-center py-4 text-muted">Aucune écriture trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="9" class="text-end px-4 py-2">Totaux :</td>
                            <td class="text-end text-primary"><?php echo e(number_format($totalDebit, 2, ',', ' ')); ?></td>
                            <td class="text-end pe-3 text-primary"><?php echo e(number_format($totalCredit, 2, ',', ' ')); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            <?php echo e($entries->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/pages/accounting/index.blade.php ENDPATH**/ ?>