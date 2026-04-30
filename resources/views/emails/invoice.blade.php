<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Facture</title>
    <style>
        body { font-family: Arial, sans-serif; color: #153b63; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #153b63, #1a4d82); padding: 35px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-style: italic; font-weight: bold; }
        .header p { color: rgba(255,255,255,0.8); margin: 5px 0 0; font-size: 14px; }
        .orange-badge { display: inline-block; background: #f2994a; color: white; padding: 5px 16px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-top: 10px; }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: bold; margin-bottom: 15px; }
        .info-box { background: #f8f9ff; border-left: 4px solid #f2994a; border-radius: 4px; padding: 20px 25px; margin: 25px 0; }
        .info-box table { width: 100%; border-collapse: collapse; }
        .info-box td { padding: 8px 0; font-size: 14px; }
        .info-box td:first-child { color: #666; width: 45%; }
        .info-box td:last-child { font-weight: bold; color: #153b63; }
        .total-row td { border-top: 2px solid #f2994a; padding-top: 12px !important; font-size: 16px !important; }
        .message { font-size: 14px; line-height: 1.8; color: #555; margin: 20px 0; }
        .footer { background: #f8f8f8; border-top: 2px solid #f2994a; padding: 20px 40px; text-align: center; font-size: 11px; color: #999; }
        .footer strong { color: #153b63; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Universal Invest Strategy.SARL</h1>
            <p>RUE EL AARAR ET BD LALLA YACOUT</p>
            <span class="orange-badge">VOTRE FACTURE</span>
        </div>
        <div class="body">
            @php
                $clientName = $invoice->contract->client->company->company_name
                    ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name);
                $htAmount = (float) $invoice->amount;
                $vatAmount = round($htAmount * 0.20, 2);
                $ttcAmount = $htAmount + $vatAmount;
            @endphp
            <div class="greeting">Bonjour {{ $clientName }},</div>
            <div class="message">
                Veuillez trouver ci-joint votre facture en format PDF. Nous vous prions de bien vouloir procéder au règlement dans les meilleurs délais.
            </div>
            <div class="info-box">
                <table>
                    <tr>
                        <td>N° Facture :</td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>Date :</td>
                        <td>{{ optional($invoice->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Service :</td>
                        <td>{{ $invoice->contract->type }}</td>
                    </tr>
                    <tr>
                        <td>Montant HT :</td>
                        <td>{{ number_format($htAmount, 2, ',', ' ') }} MAD</td>
                    </tr>
                    <tr>
                        <td>TVA 20% :</td>
                        <td>{{ number_format($vatAmount, 2, ',', ' ') }} MAD</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total TTC :</td>
                        <td>{{ number_format($ttcAmount, 2, ',', ' ') }} MAD</td>
                    </tr>
                </table>
            </div>
            <div class="message">
                Pour tout renseignement, contactez-nous :<br><br>
                📧 contact@uivstrategy.ma<br>
                📞 212522273011
            </div>
        </div>
        <div class="footer">
            <strong>Universal Invest Strategy SARL</strong><br>
            Angle Rue al AARAR et av Lalla Yacout 3eme étage Appartement 8<br>
            ICE: 002752348000050 | RC: 496151 | IF: 50137892 | CNSS: 2507310
        </div>
    </div>
</body>
</html>
