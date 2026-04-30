<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #f8fafc; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #1e3a8a; margin: 0;">Rappel d'échéance de contrat</h2>
        </div>
        <div class="content">
            <p>Bonjour {{ $contract->client->first_name }},</p>
            <p>Nous vous informons que votre contrat de type <strong>{{ $contract->type }}</strong> arrive à échéance dans <strong>{{ $daysLeft }} jours</strong> (le {{ $contract->end_date->format('d/m/Y') }}).</p>
            <p>Si vous souhaitez renouveler votre contrat et continuer à bénéficier de nos services, vous pouvez nous contacter ou vous rendre sur votre portail client.</p>
            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" class="btn">Accéder à mon compte</a>
            </p>
            <p>Cordialement,<br>L'équipe Universal Invest Strategy</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Universal Invest Strategy. Tous droits réservés.
        </div>
    </div>
</body>
</html>
