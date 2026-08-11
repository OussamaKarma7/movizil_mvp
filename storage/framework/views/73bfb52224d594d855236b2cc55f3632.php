<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Facture - Universal Invest Strategy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #153b63; background: #f9f9f9; margin: 0; padding: 0; }
        .wrapper { background-color: #f9f9f9; padding: 40px 20px; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 10px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .header { background: #ffffff; border-bottom: 2px solid #f2994a; padding: 30px 40px; text-align: center; }
        .header h1 { color: #153b63; margin: 0; font-size: 22px; font-style: italic; font-weight: bold; }
        .header p { color: #555; margin: 8px 0 0; font-size: 13px; font-weight: normal; }
        
        .body { padding: 40px; background-color: #ffffff; }
        .greeting { font-size: 18px; font-weight: bold; color: #153b63; margin-bottom: 20px; }
        .message { font-size: 15px; line-height: 1.6; color: #444; margin: 20px 0; }
        
        .invoice-box { background: #ffffff; border: 1px solid #f2994a; border-radius: 4px; overflow: hidden; margin: 30px 0; }
        .invoice-header { background: #f2994a; color: white; padding: 12px 20px; font-weight: bold; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .invoice-body { padding: 20px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 10px 0; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        .info-table td:first-child { color: #777; width: 40%; font-weight: 500; }
        .info-table td:last-child { font-weight: bold; color: #153b63; text-align: right; }
        .info-table tr:last-child td { border-bottom: none; }
        .total-row td:last-child { color: #f2994a; font-size: 18px; }
        
        .footer { background: #fdfdfd; padding: 25px 40px; text-align: center; font-size: 11px; color: #888; border-top: 1px solid #eee; }
        .footer strong { color: #153b63; display: block; margin-bottom: 5px; }
        .footer-line { margin: 10px auto; width: 50px; height: 1px; background: #ddd; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Universal Invest Strategy.SARL</h1>
                <p>Expertise & Conseil en Investissement</p>
            </div>
            <div class="body">
                <?php
                    $clientName = $invoice->contract->client->company->company_name
                        ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name);
                    $htAmount = (float) $invoice->amount;
                    $vatAmount = round($htAmount * 0.20, 2);
                    $ttcAmount = $htAmount + $vatAmount;
                ?>
                <div class="greeting">Bonjour <?php echo e($clientName); ?>,</div>
                <div class="message">
                    Nous avons le plaisir de vous transmettre votre facture n° <strong><?php echo e($invoice->invoice_number); ?></strong>.
                    <br><br>
                    Le document complet est disponible en pièce jointe au format PDF.
                </div>
                
                <div class="invoice-box">
                    <div class="invoice-header">Résumé de la facture</div>
                    <div class="invoice-body">
                        <table class="info-table">
                            <tr>
                                <td>Date d'émission :</td>
                                <td><?php echo e(optional($invoice->date)->format('d/m/Y')); ?></td>
                            </tr>
                            <tr>
                                <td>Service :</td>
                                <td><?php echo e($invoice->contract->type); ?></td>
                            </tr>
                            <tr>
                                <td>Montant HT :</td>
                                <td><?php echo e(number_format($htAmount, 2, ',', ' ')); ?> MAD</td>
                            </tr>
                            <tr>
                                <td>TVA (20%) :</td>
                                <td><?php echo e(number_format($vatAmount, 2, ',', ' ')); ?> MAD</td>
                            </tr>
                            <tr class="total-row">
                                <td>Total TTC :</td>
                                <td><?php echo e(number_format($ttcAmount, 2, ',', ' ')); ?> MAD</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="message">
                    Pour toute assistance, nos experts sont à votre disposition par e-mail à <a href="mailto:contact@uivstrategy.ma" style="color: #f2994a; text-decoration: none; font-weight: bold;">contact@uivstrategy.ma</a> ou par téléphone au <strong>212522273011</strong>.
                </div>
            </div>
            <div class="footer">
                <strong>Universal Invest Strategy SARL</strong>
                Angle Rue al AARAR et av Lalla Yacout 3eme étage Appartement 8
                <div class="footer-line"></div>
                ICE: 002752348000050 | RC: 496151 | IF: 50137892 | CNSS: 2507310
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\V.finale finale\saas-accounting\resources\views/emails/invoice.blade.php ENDPATH**/ ?>