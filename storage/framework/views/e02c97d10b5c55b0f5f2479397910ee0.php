<?php $__env->startSection('title', 'Aperçu du Contrat'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .contract-paper {
        background: #fff;
        max-width: 800px;
        margin: 20px auto;
        padding: 60px 50px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        font-family: 'Times New Roman', Times, serif;
    }
    .contract-header {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .contract-title {
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 5px;
    }
    .contract-ref {
        font-size: 14px;
        color: #666;
    }
    .contract-body {
        font-size: 16px;
        line-height: 1.6;
        color: #111;
        text-align: justify;
    }
    .contract-section {
        margin-bottom: 25px;
    }
    .contract-section-title {
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 10px;
    }
    .highlight-data {
        font-weight: bold;
        background-color: rgba(79, 70, 229, 0.05);
        padding: 0 5px;
        border-radius: 3px;
        color: #1e293b;
    }
    .signature-area {
        margin-top: 50px;
        display: flex;
        justify-content: space-between;
    }
    .signature-box {
        width: 45%;
        text-align: center;
    }
    .signature-line {
        border-bottom: 1px solid #000;
        margin-top: 60px;
        margin-bottom: 10px;
    }
    .action-bar {
        max-width: 800px;
        margin: 20px auto 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    
    <?php if(session('success')): ?>
    <div class="alert alert-success max-width-800 mx-auto mb-4">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <!-- Action Bar -->
    <div class="action-bar bg-white p-3 rounded-3 shadow-sm border">
        <div>
            <?php if($contract->status == 'pending'): ?>
                <span class="badge bg-warning text-dark mb-1">Demande en attente</span>
                <p class="mb-0 text-secondary fw-medium small">Cette demande doit être approuvée pour générer la facture.</p>
            <?php else: ?>
                <span class="badge bg-success mb-1">Contrat Actif</span>
                <p class="mb-0 text-secondary fw-medium small">Statut : Prêt pour impression / Génération PDF</p>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <?php if($contract->status == 'pending'): ?>
                <form action="<?php echo e(route('contracts.approve', $contract->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-2"></i>Approuver la Demande</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('contracts.renew', $contract->id)); ?>" class="btn btn-info text-white"><i class="fa-solid fa-sync me-2"></i>Renouveler</a>
                <button class="btn btn-light border" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Imprimer</button>
                <a href="<?php echo e(route('contracts.pdf', $contract->id)); ?>" class="btn btn-primary"><i class="fa-solid fa-file-pdf me-2"></i>Générer PDF</a>
                <a href="<?php echo e(route('contracts.word', $contract->id)); ?>" class="btn btn-outline-dark"><i class="fa-solid fa-file-word me-2"></i>Télécharger Word</a>
                <form action="<?php echo e(route('contracts.sendEmail', $contract->id)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Envoyer le contrat par email à <?php echo e($contract->client->email); ?> ?')">
                        <i class="fa-solid fa-envelope me-2"></i>Envoyer par Email
                    </button>
                </form>
                <a href="<?php echo e(route('contracts.edit', $contract->id)); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-pen-to-square me-2"></i>Modifier</a>
                <form action="<?php echo e(route('contracts.destroy', $contract->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-trash me-2"></i>Supprimer</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if($contract->is_renewal || $contract->renewals->count() > 0): ?>
    <div class="action-bar bg-light p-3 rounded-3 shadow-sm border mt-3">
        <div class="w-100">
            <h6 class="mb-2 fw-bold text-info"><i class="fa-solid fa-history me-2"></i>Historique des Renouvellements</h6>
            <div class="d-flex flex-wrap gap-2">
                <?php if($contract->is_renewal && $contract->original): ?>
                    <a href="<?php echo e(route('contracts.show', $contract->parent_id)); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-backward me-1"></i> Contrat Original (#<?php echo e($contract->parent_id); ?>)
                    </a>
                <?php endif; ?>
                <?php $__currentLoopData = $contract->renewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $renewal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('contracts.show', $renewal->id)); ?>" class="btn btn-sm btn-outline-info">
                        #<?php echo e($renewal->id); ?> - <?php echo e($renewal->start_date ? $renewal->start_date->format('d/m/Y') : 'N/A'); ?> <i class="fa-solid fa-forward ms-1"></i>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="action-bar bg-white p-3 rounded-3 shadow-sm border">
        <div class="w-100">
            <h6 class="mb-3 fw-bold">Informations client recues</h6>
            <div class="row g-2 small">
                <div class="col-md-4"><strong>Nom:</strong> <?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></div>
                <div class="col-md-4"><strong>Date naissance:</strong> <?php echo e(optional($contract->client->birth_date)->format('d/m/Y') ?? '-'); ?></div>
                <div class="col-md-4"><strong>Telephone:</strong> <?php echo e($contract->client->phone); ?></div>
                <div class="col-md-4"><strong>Email:</strong> <?php echo e($contract->client->email); ?></div>
                <div class="col-md-8"><strong>Adresse:</strong> <?php echo e($contract->client->address); ?></div>
                <div class="col-md-4"><strong>Entreprise:</strong> <?php echo e($contract->client->company->company_name ?? '-'); ?></div>
                <div class="col-md-2"><strong>RC:</strong> <?php echo e($contract->client->company->rc ?? '-'); ?></div>
                <div class="col-md-2"><strong>RCE:</strong> <?php echo e($contract->client->company->rce ?? '-'); ?></div>
                <div class="col-md-2"><strong>ICE:</strong> <?php echo e($contract->client->company->ice ?? '-'); ?></div>
                <div class="col-md-2"><strong>IF:</strong> <?php echo e($contract->client->company->if ?? '-'); ?></div>
                <div class="col-md-6"><strong>Forme juridique:</strong> <?php echo e($contract->client->company->legal_form ?? '-'); ?></div>
                <div class="col-md-6"><strong>Activite:</strong> <?php echo e($contract->client->company->activity ?? '-'); ?></div>
                <div class="col-md-12"><strong>Siege social:</strong> <?php echo e($contract->client->company->headquarters_address ?? '-'); ?></div>
            </div>
        </div>
    </div>

    <div class="action-bar bg-white p-3 rounded-3 shadow-sm border">
        <div class="w-100">
            <h6 class="mb-3 fw-bold text-dark"><i class="fa-solid fa-file-shield me-2 text-primary"></i>Documents Officiels (Téléchargements)</h6>
            <div class="d-flex gap-3">
                <a href="<?php echo e(route('contracts.attachment', ['id' => $contract->id, 'type' => 'cin'])); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-id-card me-2"></i> Copie CIN
                </a>
                <a href="<?php echo e(route('contracts.attachment', ['id' => $contract->id, 'type' => 'certificat'])); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-certificate me-2"></i> Certificat Négatif
                </a>
            </div>
        </div>
    </div>

    <!-- Contract Paper -->
    <div class="contract-paper">
        <div class="contract-header">
            <h1 class="contract-title">CONTRAT DE PRESTATION DE SERVICES</h1>
            <div class="contract-ref">Référence : CTR-<?php echo e(date('Y')); ?>-<?php echo e(str_pad($contract->id, 5, '0', STR_PAD_LEFT)); ?> &nbsp; | &nbsp; Date : <?php echo e(now()->format('d/m/Y')); ?></div>
        </div>

        <div class="contract-body">
            
            <div class="contract-section">
                <strong>ENTRE LES SOUSSIGNÉS :</strong><br><br>
                <strong>UNIVERSAL INVEST STRATEGY</strong>, société à responsabilité limitée, dont le siège social est situé à Casablanca, Maroc, représentée par son Gérant.<br>
                <em>Ci-après dénommée le <strong>"Prestataire"</strong></em>
            </div>

            <div class="text-center my-3 fw-bold">ET</div>

            <div class="contract-section">
                <strong><span class="highlight-data"><?php echo e($contract->client->company->company_name ?? ($contract->client->first_name . ' ' . $contract->client->last_name)); ?></span></strong>, 
                <?php if($contract->client->company): ?>
                société avec ICE <span class="highlight-data"><?php echo e($contract->client->company->ice); ?></span>, RC <span class="highlight-data"><?php echo e($contract->client->company->rc); ?></span>, IF <span class="highlight-data"><?php echo e($contract->client->company->if); ?></span>,
                <?php endif; ?>
                domicilié à <span class="highlight-data"><?php echo e($contract->client->address); ?></span>, représenté par <span class="highlight-data"><?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></span> (CIN: <span class="highlight-data"><?php echo e($contract->client->cin); ?></span>).<br>
                <em>Ci-après dénommée le <strong>"Client"</strong></em>
            </div>

            <hr class="my-4" style="opacity: 0.1;">

            <div class="contract-section">
                <div class="contract-section-title">ARTICLE 1 : OBJET DU CONTRAT</div>
                Le Client confie au Prestataire la mission de <span class="highlight-data"><?php echo e($contract->type); ?></span> pour son activité. Le Prestataire accepte et s'engage à exécuter cette mission avec professionnalisme et diligence conformément aux lois en vigueur.
            </div>

            <div class="contract-section">
                <div class="contract-section-title">ARTICLE 2 : DURÉE</div>
                Le présent contrat est conclu pour une durée de <span class="highlight-data"><?php echo e($contract->duration); ?> Mois</span>, débutant le <span class="highlight-data"><?php echo e($contract->start_date ? $contract->start_date->format('d/m/Y') : '-'); ?></span> jusqu'au <span class="highlight-data"><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : '-'); ?></span>. Il est renouvelable par tacite reconduction sauf dénonciation par l'une des parties par courrier avec un préavis de 30 jours.
            </div>

            <div class="contract-section">
                <div class="contract-section-title">ARTICLE 3 : CONDITIONS FINANCIÈRES</div>
                En contrepartie des services rendus, le Client s'engage à payer au Prestataire des honoraires globaux de <span class="highlight-data"><?php echo e(number_format($contract->price, 2)); ?> MAD</span> Hors Taxes. Ce montant correspond au forfait de la durée choisie. Des factures seront générées et transmises en conséquence.
            </div>

            <div class="contract-section">
                <div class="contract-section-title">ARTICLE 4 : OBLIGATIONS ET CONFIDENTIALITÉ</div>
                Le Prestataire garantit la confidentialité totale des données du Client, des écritures comptables et des modèles d'affaires. Le Client s'engage à fournir tous les documents requis et factures au Prestataire en temps utile pour faciliter la prestation.
            </div>

            <div class="contract-section mt-5">
                Fait à <strong>Casablanca</strong>, le <strong><?php echo e(now()->format('d/m/Y')); ?></strong>, en deux exemplaires originaux.
            </div>

            <!-- Signatures -->
            <div class="signature-area">
                <div class="signature-box">
                    <strong>Le Prestataire</strong><br>
                    <small>(Lu et approuvé)</small>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-box">
                    <strong>Le Client</strong><br>
                    <small><?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></small><br>
                    <small>(Lu et approuvé)</small>
                    <div class="signature-line"></div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/pages/contracts/show.blade.php ENDPATH**/ ?>