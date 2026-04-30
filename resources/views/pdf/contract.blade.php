<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.5; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        h1, h2, h3, h4 { margin: 0 0 10px 0; }
        .header-content { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .footer { font-size: 11px; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; margin-top: 50px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- ATTESTATION DE DOMICILIATION -->
    <div class="header-content">
        <h3 class="font-bold">UNIVERSAL INVEST STRATEGY</h3>
        - Domiciliation Juridique<br>
        - Centre d’Affaires<br>
        - Conseil Juridique – Fiscal et Comptable<br>
        - Tenue de Comptabilité<br>
        - Diagnostic des entreprises<br>
        - Audit
    </div>

    <h2 class="text-center font-bold mb-4" style="text-decoration: underline;">ATTESTATION DE DOMICILIATION</h2>

    <p>Nous soussignés, <strong>UNIVERSAL INVEST STRATEGY SARL AU</strong>, attestons par la présente que :</p>

    @if($contract->client->company && $contract->client->company->company_name)
    <p>La société <strong>«{{ $contract->client->company->company_name }}»</strong> (ICE: {{ $contract->client->company->ice }}, RC: {{ $contract->client->company->rc }}, IF: {{ $contract->client->company->if }}) a domicilié son adresse fiscale dans nos locaux situés à :</p>
    @else
    <p>Monsieur/Madame <strong>{{ $contract->client->last_name }} {{ $contract->client->first_name }}</strong> a domicilié son adresse fiscale dans nos locaux situés à :</p>
    @endif

    <p class="font-bold">ANGLE RUE EL AARAR et BD LALLA ELYACOUT, IMM1, 3ème ETAGE, APPT 8</p>
    
    <p>pour une période allant du <strong>{{ $contract->start_date ? $contract->start_date->format('d/m/Y') : '' }}</strong> au <strong>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'inconnue' }}</strong>.</p>

    <p>Nous déclarant en outre avoir pris connaissance qu’en application des dispositions de l’article 93 du CRCP, les rôles d’impôts, états de produits et autres titres de perception régulièrement émis sont exécutoires contre les redevables qui y sont inscrits, toutes personnes auprès desquelles les redevables ont élu domicile fiscal, avec leur accord.</p>

    <p>Les personnes auprès desquelles les redevables ont élu domicile fiscal avec accord, peuvent, de ce fait, faire l’objet d’action en recouvrement au même titre que les redevables à raison de la créance due au titre de l’activité concernée par la domiciliation.</p>

    <p>En foi de quoi, la présente attestation est délivrée pour lui permettre de procéder aux formalités administratives.</p>

    <div class="text-right mb-4">
        <p>Fait à Casablanca Le : <strong>{{ now()->format('d/m/Y') }}</strong></p>
        <p class="font-bold">BACHRA YOUSSEF</p>
    </div>

    <div class="footer">
        Angle Rue Al AARAR et Av Lalla El Yacout 3ème, imm1 Appartement 8 Tél:+212600800747<br>
        Email: contact@ui-strategy.com<br>
        RC : 496151 – patente : 34102034 – I.F : 50137892 – CNSS : 2507310 ICE:002752348000050
    </div>

    <div class="page-break"></div>

    <!-- CONTRAT DE DOMICILIATION -->
    <h2 class="text-center font-bold mb-4" style="text-decoration: underline;">CONTRAT DE DOMICILIATION</h2>

    <p>A/ Le cabinet <strong>‘’ UNIVERSAL INVEST STRATEGY ‘’ SARL AU</strong>, représenté par son gérant-unique M. YOUSSEF BACHRA titulaire de la CIN N° BE604671, ci-après dénommé ‘UNIVERSAL INVEST STRATEGY’’, d’une part, et d’autre part :</p>

    @if($contract->client->company && $contract->client->company->company_name)
    <p>La société <strong>« {{ $contract->client->company->company_name }} »</strong> SARL AU (ICE: {{ $contract->client->company->ice }}, RC: {{ $contract->client->company->rc }}, IF: {{ $contract->client->company->if }}) représentée par :<br>
    @else
    <p>
    @endif
    Mr/Mme <strong>{{ Str::upper($contract->client->last_name) }} {{ Str::upper($contract->client->first_name) }}</strong> de nationalité Marocaine, titulaire de CIN n°{{ $contract->client->cin }}, Téléphone N°: {{ $contract->client->phone }}, adresse Email: {{ $contract->client->email }}, demeurant à {{ $contract->client->address }}.</p>

    <p>La présente domiciliation est établie dans le cadre de la loi marocaine, notamment les mesures engagées pour faciliter l’investissement de la création d’entreprise par les jeunes promoteurs. Elle est aussi régie par le code des obligations et contrats ainsi que par les documents annexes à la présente domiciliation.</p>

    <h4 class="font-bold">ARTICLE II - OBJET</h4>
    <p>Par le présent engagement de domiciliation, le cabinet UNIVERSAL INVEST STRATEGY SARL AU s’engage moyennant une rétribution Mensuelle, à mettre à la disposition du DOMICILIE qui accepte pour la durée et aux conditions fixées par la loi marocaine et par les conditions particulières et Générales, établies dans le présent engagement de domiciliation, un ensemble de prestations tel que défini ci-après:<br>
    La domiciliation de son entreprise (siège social et adresse commerciale); La réception, la tutelle et la mise à disposition du courrier reçu; Réception des télécopies.</p>

    <h4 class="font-bold">ARTICLE III - DUREE</h4>
    <p>La présente domiciliation commence à courir Du <strong>{{ $contract->start_date ? $contract->start_date->format('d/m/Y') : '' }}</strong> au <strong>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : '' }}</strong>.<br>
    Elle sera résiliée automatiquement sans préavis ou un écrit fait par UNIVERSAL INVEST STRATEGY.<br>
    Et UNIVERSAL INVEST STRATEGY n'est plus responsable des préjudices générés par le client.<br>
    Elle sera renouvelée dans le cas où le client fait une demande écrite acceptée par UNIVERSAL INVEST STRATEGY.</p>

    <h4 class="font-bold">ARTICLE IV – OBLIGATION DU DOMICILIE</h4>
    <p>Le DOMICILIE s’engage à régler, aux échéances de renouvellement, les redevances relatives au frais de domiciliation ainsi que tous les frais annexe facturés soit 165MAD/mois.<br>
    Le DOMICILIE s’engage à déclarer sans délai au domiciliataire selon les cas, soit tout changement relatif à son domicile personnel, soit s’il s’agit d’une personne morale, tout changement relatif à sa forme juridique, son objet, ainsi qu’au nom et au domicile personnel des personnes ayant le pouvoir générale de l’engager.<br>
    En cas de non-respect des présentes, ‘UNIVERSAL INVEST STRATEGY’ SARLAU pourra unilatéralement et à tout moment révoquer sans formalité ni indemnité le présent engagement de domiciliation, ses obligations seront alors suspendues sans contrepartie de plein droit et sa responsabilité dégagée.<br>
    Le DOMICILIE s’oblige à remettre annuellement à la société domiciliataire les copies des reçus attestant du dépôt des différentes déclarations fiscales exigibles par la loi marocaine, notamment le bilan annuel et les déclarations de TVA.</p>

    <h4 class="font-bold">ARTICLE V – RESILIATION DU CONTRAT</h4>
    <p>Le présent contrat pourra être résilié de plein droit par le cabinet ‘‘UNIVERSAL INVEST STRATEGY’’ SARLAU, 30 jours après l’envoi au DOMICILIE d’une mise en demeure par lettre recommandée avec avis de réception, restée sans effet dans les cas suivant: Non observation par le DOMICILIE de l’une quelconque des dispositions du présent engagement; Non-paiement à leur échéance, des honoraires et/ou prestation de service; Défaut de dépôt de la déclaration fiscale légale; Défaut d’information du cabinet ‘UNIVERSAL INVEST STRATEGY’’ SARLAU d’un éventuel changement dans sa situation.</p>

    <h4 class="font-bold">ARTICLE VI – ELECTION DE DOMICILE</h4>
    <p>Pour l’exécution des présentes, les parties font élections de domicile chacune à son adresse portée sur le présent contrat et pour le DOMICILIE à son adresse personnelle ou à celle de son représentant légal. Tout changement d’adresse du DOMICILIE n’est opposable au cabinet ‘’ UNIVERSAL INVEST STRATEGY’ ’SARL AU que s’il lui a été notifié par le DOMICILIE par lettre recommandée avec accusé de réception.<br>
    UNIVERSAL INVEST STRATEGY informe les autorités comptables, l’administration des impôts, la trésorerie générale et de l’administration de la Douane, le cas échéant, dans un délai n’excédant pas 15 jours de la date de réception des plis recommandés par les services fiscaux qui n’auront pas été remis aux personnes domiciliées.<br>
    <em>N.B : Cette attestation de domiciliation est délivrée pour la création d’une nouvelle société et n’est pas valable pour un transfert de siège social.</em></p>

    <h4 class="font-bold">ARTICLE VII – FRAIS</h4>
    <p>Les frais de légalisation sont Supportés par le domicilié.</p>

    <div style="margin-top: 40px; display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p class="font-bold">Mr/Mme. {{ Str::upper($contract->client->last_name) }} {{ Str::upper($contract->client->first_name) }}</p>
        </div>
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p class="font-bold">Mr. YOUSSEF BACHRA</p>
        </div>
    </div>

</body>
</html>
