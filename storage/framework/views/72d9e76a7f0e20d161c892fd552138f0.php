<?php $__env->startSection('title', 'Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Liste des clients</h6>
            <div class="d-flex gap-2">
                <form action="<?php echo e(route('export.direct.sync')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-sync-alt me-1"></i> Synchroniser tout vers Sage
                    </button>
                </form>
                <form method="GET" action="<?php echo e(route('clients.index')); ?>" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nom, email, CIN, ICE..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-sm btn-primary" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Entreprise</th>
                        <th>Contrats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($client->first_name); ?> <?php echo e($client->last_name); ?></div>
                                <small class="text-muted">CIN: <?php echo e($client->cin); ?></small>
                            </td>
                            <td><?php echo e($client->email); ?></td>
                            <td><?php echo e($client->phone); ?></td>
                            <td><?php echo e($client->company->company_name ?? '-'); ?></td>
                            <td><?php echo e($client->contracts->count()); ?></td>
                            <td>
                                <a href="<?php echo e(route('clients.show', $client->id)); ?>" class="btn btn-sm btn-outline-primary">Voir details</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucun client trouve.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($clients->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\saas-accounting\resources\views/pages/clients/index.blade.php ENDPATH**/ ?>