<?php $__env->startSection('title', 'Espace Client'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Bienvenue <?php echo e($client->first_name); ?> <?php echo e($client->last_name); ?></h5>
                <p class="mb-0 text-muted">Vous pouvez soumettre une demande de contrat et suivre vos contrats/factures.</p>
            </div>
            <a href="<?php echo e(route('client.contract.create')); ?>" class="btn btn-primary">Nouvelle demande de contrat</a>
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
                        <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($contract->type); ?></td>
                                <td><?php echo e(optional($contract->start_date)->format('d/m/Y')); ?> - <?php echo e(optional($contract->end_date)->format('d/m/Y')); ?></td>
                                <td>
                                    <span class="badge <?php echo e($contract->status === 'active' ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                        <?php echo e($contract->status === 'active' ? 'Actif' : 'En attente'); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($contract->status === 'active'): ?>
                                        <div class="d-flex gap-1">
                                            <a href="<?php echo e(route('client.contracts.pdf', $contract->id)); ?>" class="btn btn-sm btn-outline-primary">PDF</a>
                                            <a href="<?php echo e(route('client.contracts.word', $contract->id)); ?>" class="btn btn-sm btn-outline-dark">Word</a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Disponible apres approbation</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Aucun contrat</td></tr>
                        <?php endif; ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($invoice->invoice_number); ?></td>
                                <td><?php echo e(number_format($invoice->amount, 2)); ?> MAD</td>
                                <td><a href="<?php echo e(route('client.invoices.pdf', $invoice->id)); ?>" class="btn btn-sm btn-outline-primary">PDF</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucune facture</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\o.karmaoui\Downloads\movizil\movizil\resources\views/pages/client/dashboard.blade.php ENDPATH**/ ?>