<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de domiciliation</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 12pt; line-height: 1.5; color: #111; }
        h2 { text-align: center; text-decoration: underline; margin-bottom: 18px; }
        .section-title { font-weight: bold; text-decoration: underline; margin-top: 14px; }
        .signatures { margin-top: 40px; width: 100%; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; }
        .small { font-size: 10pt; }
    </style>
</head>
<body>
    <p><strong>UNIVERSAL INVEST STRATEGY</strong><br>
    - Domiciliation Juridique<br>
    - Centre d'Affaires<br>
    - Conseil Juridique - Fiscal et Comptable<br>
    - Tenue de Comptabilite<br>
    - Diagnostic des entreprises<br>
    - Audit</p>

    <h2>ATTESTATION DE DOMICILIATION</h2>
    <p>Nous soussignes, <strong>UNIVERSAL INVEST STRATEGY SARL AU</strong>, attestons par la presente que :</p>

    <?php if($contract->client->company && $contract->client->company->company_name): ?>
        <p>La societe <strong><?php echo e($contract->client->company->company_name); ?></strong> a domicilie son adresse fiscale dans nos locaux pour la periode du <strong><?php echo e(optional($contract->start_date)->format('d/m/Y')); ?></strong> au <strong><?php echo e(optional($contract->end_date)->format('d/m/Y')); ?></strong>.</p>
    <?php else: ?>
        <p>M./Mme <strong><?php echo e($contract->client->first_name); ?> <?php echo e($contract->client->last_name); ?></strong> a domicilie son adresse fiscale dans nos locaux pour la periode du <strong><?php echo e(optional($contract->start_date)->format('d/m/Y')); ?></strong> au <strong><?php echo e(optional($contract->end_date)->format('d/m/Y')); ?></strong>.</p>
    <?php endif; ?>

    <p><strong>Adresse de domiciliation :</strong> ANGLE RUE EL AARAR et BD LALLA ELYACOUT, IMM1, 3eme ETAGE, APPT 8 - Casablanca.</p>
    <p>Fait a Casablanca, le <strong><?php echo e(now()->format('d/m/Y')); ?></strong>.</p>
    <p><strong>BACHRA YOUSSEF</strong></p>

    <hr>

    <h2>CONTRAT DE DOMICILIATION</h2>
    <p>A/ Le cabinet <strong>UNIVERSAL INVEST STRATEGY</strong> SARL AU, represente par son gerant-unique <strong>M. YOUSSEF BACHRA</strong>, titulaire CIN N BE604671, ci-apres denomme UNIVERSAL INVEST STRATEGY, d'une part, et d'autre part :</p>

    <p>
        <?php if($contract->client->company && $contract->client->company->company_name): ?>
            La societe <strong><?php echo e($contract->client->company->company_name); ?></strong>,
            RC: <?php echo e($contract->client->company->rc ?? '-'); ?>,
            RCE: <?php echo e($contract->client->company->rce ?? '-'); ?>,
            ICE: <?php echo e($contract->client->company->ice ?? '-'); ?>,
            IF: <?php echo e($contract->client->company->if ?? '-'); ?>,
            representee par
        <?php endif; ?>
        <strong><?php echo e(strtoupper($contract->client->first_name . ' ' . $contract->client->last_name)); ?></strong>,
        ne(e) le <?php echo e(optional($contract->client->birth_date)->format('d/m/Y') ?? '-'); ?>,
        CIN <?php echo e($contract->client->cin); ?>,
        Tel <?php echo e($contract->client->phone); ?>,
        Email <?php echo e($contract->client->email); ?>,
        demeurant a <?php echo e($contract->client->address); ?>.
    </p>

    <p class="section-title">ARTICLE II - OBJET</p>
    <p>Le domiciliataire met a disposition du domicilie les prestations suivantes : domiciliation du siege social, reception et mise a disposition du courrier, reception de telecopies.</p>

    <p class="section-title">ARTICLE III - DUREE</p>
    <p>La domiciliation est conclue du <strong><?php echo e(optional($contract->start_date)->format('d/m/Y')); ?></strong> au <strong><?php echo e(optional($contract->end_date)->format('d/m/Y')); ?></strong>, renouvelable selon accord ecrit.</p>

    <p class="section-title">ARTICLE IV - OBLIGATIONS DU DOMICILIE</p>
    <p>Le domicilie s'engage a regler les redevances de domiciliation, informer de tout changement legal et deposer les declarations fiscales requises.</p>

    <p class="section-title">ARTICLE V - RESILIATION</p>
    <p>Le contrat peut etre resilie de plein droit apres mise en demeure en cas de non-respect des obligations contractuelles.</p>

    <p class="section-title">ARTICLE VI - ELECTION DE DOMICILE</p>
    <p>Les parties elisent domicile a leurs adresses indiquees dans le present contrat.</p>

    <p class="section-title">ARTICLE VII - FRAIS</p>
    <p>Les frais de legalisation sont supportes par le domicilie.</p>

    <table class="signatures">
        <tr>
            <td><strong><?php echo e(strtoupper($contract->client->first_name . ' ' . $contract->client->last_name)); ?></strong></td>
            <td><strong>M. YOUSSEF BACHRA</strong></td>
        </tr>
    </table>

    <p class="small">
        UNIVERSAL INVEST STRATEGY - Angle Rue Al AARAR et Av Lalla El Yacout, 3eme, imm1 Appartement 8 - Casablanca<br>
        Tel: +212600800747 - Email: contact@ui-strategy.com - RC: 496151 - IF: 50137892 - ICE: 002752348000050
    </p>
</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\saas-accounting\resources\views/word/contract.blade.php ENDPATH**/ ?>