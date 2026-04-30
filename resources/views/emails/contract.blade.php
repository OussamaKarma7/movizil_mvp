<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Contrat</title>
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
            <span class="orange-badge">VOTRE CONTRAT</span>
        </div>
        <div class="body">
            @php
                $clientName = $contract->client->company->company_name
                    ?? ($contract->client->first_name . ' ' . $contract->client->last_name);
            @endphp
            <div class="greeting">Bonjour {{ $clientName }},</div>
            <div class="message">
                Nous vous remercions de votre confiance. Veuillez trouver ci-joint votre contrat en format PDF.
            </div>
            <div class="info-box">
                <table>
                    <tr>
                        <td>Type de contrat :</td>
                        <td>{{ $contract->type }}</td>
                    </tr>
                    <tr>
                        <td>Date de début :</td>
                        <td>{{ optional($contract->start_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Date de fin :</td>
                        <td>{{ optional($contract->end_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Durée :</td>
                        <td>{{ $contract->duration }} mois</td>
                    </tr>
                    <tr>
                        <td>Prix :</td>
                        <td>{{ number_format($contract->price, 2, ',', ' ') }} MAD</td>
                    </tr>
                </table>
            </div>
            <div class="message">
                Pour toute question concernant votre contrat, n'hésitez pas à nous contacter :
                <br><br>
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
