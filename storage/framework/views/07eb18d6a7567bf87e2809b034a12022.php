<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Times-Roman', serif;
            color: #153b63;
            margin: 0;
            padding: 40px 50px 120px 50px;
            line-height: 1.4;
            box-sizing: border-box;
        }
        .clear { clear: both; }
        
        /* Header: Logo left, Company right */
        .header-table { width: 100%; border: none; border-collapse: collapse; margin-bottom: 40px; }
        .logo-cell { width: 45%; vertical-align: top; }
        .logo-cell img { height: 110px; width: auto; display: block; }
        .info-cell { width: 55%; text-align: right; vertical-align: top; padding-top: 8px; }
        .company-name { font-size: 24px; font-weight: bold; font-style: italic; margin-bottom: 4px; color: #153b63; }
        .company-details { font-size: 11.5px; line-height: 1.5; color: #153b63; }

        /* Meta: Date left, Destinataire right */
        .meta-table { width: 100%; border: none; border-collapse: collapse; margin-bottom: 10px; }
        .date-cell { width: 50%; vertical-align: top; padding-top: 5px; }
        .dest-cell { width: 50%; text-align: right; vertical-align: top; }
        .under-italic { text-decoration: underline; font-style: italic; font-size: 13px; color: #153b63; font-weight: bold; }
        .recipient-label { text-decoration: underline; font-style: italic; font-size: 13px; font-weight: bold; color: #153b63; margin-bottom: 10px; }
        .recipient-name { font-size: 15px; font-weight: bold; color: #000; margin-top: 10px; }
        .recipient-details { font-size: 12px; font-weight: bold; color: #000; }

        /* Invoice Title */
        .invoice-title { font-size: 30px; font-weight: bold; font-style: italic; color: #1e4067; margin-top: 35px; margin-bottom: 20px; }

        /* Main Table */
        .invoice-table { width: 100%; border-collapse: collapse; border: 1px solid #f2994a; }
        .invoice-table th { 
            background-color: #f2994a; 
            color: #ffffff; 
            text-align: center; 
            padding: 12px 10px; 
            font-size: 13px; 
            font-weight: bold; 
            border: 1px solid #e67e22;
        }
        .invoice-table td { 
            padding: 30px 12px; 
            border: 1px solid #f2994a; 
            color: #000; 
            font-size: 13px; 
            vertical-align: middle;
        }

        /* Totals */
        .totals-wrapper { width: 100%; margin-top: 25px; }
        .totals-table { float: right; width: 45%; border-collapse: collapse; border: 1px solid #f2994a; }
        .totals-table td { padding: 10px 12px; border: 1px solid #f2994a; font-weight: bold; font-size: 13px; color: #000; }
        .totals-table .label { font-style: italic; text-align: right; }
        .totals-table .value { text-align: right; width: 40%; }

        /* Amount in Words */
        .amount-words { 
            text-align: center; 
            font-weight: bold; 
            text-decoration: underline; 
            color: #000; 
            font-size: 13px; 
            margin-top: 80px; 
            width: 100%;
        }

        /* Footer */
        .page-footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0 50px;
        }
        .footer-line {
            height: 2px;
            background-color: #f2994a;
            margin-bottom: 10px;
        }
        .footer-content {
            color: #1e4067;
            font-size: 9px;
            line-height: 1.5;
            font-weight: bold;
        }
    </style>
</head>
<?php
    $clientName = $invoice->contract->client->company->company_name
        ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name);
    
    $clientIce = $invoice->contract->client->company->ice ?? null;
    
    $htAmount = (float) $invoice->amount;
    $vatAmount = round($htAmount * 0.20, 2);
    $ttcAmount = $htAmount + $vatAmount;
    
    $startDateFormatted = optional($invoice->contract->start_date)->format('d/m/Y');
    $endDateFormatted = optional($invoice->contract->end_date)->format('d/m/Y');
    
    // Formatting service label to match the preview JS exactly
    $serviceLabel = $invoice->contract->type;
    if ($invoice->contract->type === 'Domiciliation' && $startDateFormatted && $endDateFormatted) {
        $serviceLabel = "Domiciliation Juridique pour la période du $startDateFormatted au $endDateFormatted";
    }
    
    $fmt = class_exists('\NumberFormatter') ? new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT) : null;
    $amountWords = $fmt ? $fmt->format($ttcAmount) : number_format($ttcAmount, 2, ',', ' ');
    
    // Ensure accurate invoice number
    $displayInvoiceNumber = preg_replace('/[^0-9]/', '', $invoice->invoice_number) ?: $invoice->invoice_number;
    
    // Logo processing
    $logoPath = public_path('uis-logo.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoBase64 = base64_encode(file_get_contents($logoPath));
    }
?>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <?php if($logoBase64): ?>
                    <img src="data:image/png;base64,<?php echo e($logoBase64); ?>" alt="UIS Logo">
                <?php else: ?>
                    <div style="padding-top: 10px;">
                        <span style="font-size: 42px; font-weight: bold; color: #153b63; font-style: italic; letter-spacing: -2px;">U</span>
                        <span style="font-size: 42px; font-weight: bold; color: #f2994a; font-style: italic; letter-spacing: -2px;">I</span>
                        <span style="font-size: 42px; font-weight: bold; color: #153b63; font-style: italic; letter-spacing: -2px;">S</span>
                        <div style="font-size: 11px; color: #153b63; font-weight: bold; margin-top: -10px;">UNIVERSAL INVEST STRATEGY</div>
                    </div>
                <?php endif; ?>
            </td>
            <td class="info-cell">
                <div class="company-name">Universal Invest Strategy.<span style="font-size:15px;">SARL</span></div>
                <div class="company-details">
                    RUE EL AARAR ET BD LALLA YACOUT<br>
                    212522273011<br>
                    contact@uivstrategy.ma
                </div>
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td class="date-cell">
                <span class="under-italic">Date de facture: <?php echo e(optional($invoice->date)->format('d/m/Y')); ?></span>
            </td>
            <td class="dest-cell">
                <div class="recipient-label">DESTINATAIRE:</div>
                <div class="recipient-name"><?php echo e(strtoupper($clientName)); ?></div>
                <div class="recipient-details">
                    <?php if($clientIce): ?>
                        ICE: <?php echo e($clientIce); ?>

                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="invoice-title">
        Facture &#8470;<?php echo e($displayInvoiceNumber); ?>

    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 50%;">DÉSIGNATION</th>
                <th style="width: 15%;">QUANTITÉ</th>
                <th style="width: 15%;">PRIX</th>
                <th style="width: 20%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($serviceLabel); ?></td>
                <td style="text-align: center;">1</td>
                <td style="text-align: right;"><?php echo e(number_format($htAmount, 2, ',', ' ')); ?></td>
                <td style="text-align: right;"><?php echo e(number_format($htAmount, 2, ',', ' ')); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="label">TOTAL HT</td>
                <td class="value"><?php echo e(number_format($htAmount, 2, ',', ' ')); ?></td>
            </tr>
            <tr>
                <td class="label">TVA 20%</td>
                <td class="value"><?php echo e(number_format($vatAmount, 2, ',', ' ')); ?></td>
            </tr>
            <tr>
                <td class="label">Total TTC</td>
                <td class="value"><?php echo e(number_format($ttcAmount, 2, ',', ' ')); ?></td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    <div class="amount-words">
        Arrêter la présente facture a la somme de <?php echo e(ucfirst($amountWords)); ?> DHS ,00 dhs
    </div>

    <div class="page-footer">
        <div class="footer-line"></div>
        <div class="footer-content">
            Angle Rue al AARAR et av Lalla Yacout 3eme étage Appartement 8 &nbsp; ICE:002752348000050<br>
            Tél:0707040170-Email: contact@uivstrategy.ma &nbsp; RC: 496151-patente: 34102034-IF: 50137892-CNSS:2507310
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/pdf/invoice.blade.php ENDPATH**/ ?>