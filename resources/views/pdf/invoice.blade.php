<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #153b63;
            margin: 0;
            padding: 40px 50px;
            line-height: 1.4;
        }
        .clear { clear: both; }
        
        /* Header Table */
        .header-table { width: 100%; border: none; margin-bottom: 40px; }
        .logo-cell { width: 45%; vertical-align: top; }
        .logo-cell img { height: 115px; width: auto; }
        .info-cell { width: 55%; text-align: right; vertical-align: top; padding-top: 10px; }
        .company-name { font-size: 26px; font-weight: bold; font-style: italic; margin-bottom: 2px; }
        .company-details { font-size: 11px; line-height: 1.2; }

        /* Meta Table */
        .meta-table { width: 100%; margin-bottom: 30px; }
        .date-cell { width: 50%; vertical-align: top; text-align: left; }
        .dest-cell { width: 50%; vertical-align: top; text-align: right; }
        .under-italic { text-decoration: underline; font-style: italic; font-size: 13px; font-weight: bold; }
        .recipient-box { margin-top: 15px; font-weight: bold; text-align: right; }
        .recipient-name { font-size: 14px; margin-bottom: 2px; }

        /* Title */
        .invoice-title { margin-top: 40px; font-size: 28px; font-weight: bold; font-style: italic; color: #153b63; text-align: left; }

        /* Main Table */
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .invoice-table th { 
            background-color: #f2994a; 
            color: white; 
            padding: 10px; 
            font-weight: bold; 
            text-align: center; 
            border: 1px solid #f49141;
            font-size: 12px;
        }
        .invoice-table td { 
            padding: 25px 10px; 
            border: 1px solid #f2994a; 
            vertical-align: middle;
            color: black;
            font-weight: 500;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Totals Table */
        .totals-wrapper { width: 100%; margin-top: 20px; }
        .totals-table { float: right; width: 45%; border-collapse: collapse; }
        .totals-table td { padding: 8px 10px; border: 1px solid #f2994a; font-size: 12px; }
        .totals-table .label { font-weight: bold; font-style: italic; text-align: right; color: black; }
        .totals-table .value { text-align: right; width: 40%; color: black; font-weight: bold; }
        .totals-table .grand-total { border-bottom: 4px double #f2994a !important; }

        /* Amount in Words */
        .amount-words { 
            margin-top: 70px; 
            text-align: center; 
            font-weight: bold; 
            text-decoration: underline;
            font-size: 13px;
            color: black;
            width: 100%;
        }

        /* Footer */
        .footer-line {
            position: fixed;
            bottom: 40px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #f2994a;
            margin: 0 50px;
        }
        .footer-content {
            position: fixed;
            bottom: 10px;
            left: 50px;
            right: 50px;
            text-align: center;
            font-size: 9px;
            color: #153b63;
            line-height: 1.3;
        }
    </style>
</head>
@php
    $clientName = $invoice->contract->client->company->company_name
        ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name);
    $htAmount = (float) $invoice->amount;
    $vatAmount = round($htAmount * 0.20, 2);
    $ttcAmount = $htAmount + $vatAmount;
    
    $startDate = optional($invoice->contract->start_date)->format('m/Y');
    $endDate = optional($invoice->contract->end_date)->format('m/Y');
    $serviceLabel = $invoice->contract->type;
    if ($startDate) $serviceLabel .= ' du ' . $startDate;
    if ($endDate) $serviceLabel .= ' au ' . $endDate;

    $fmt = class_exists('\NumberFormatter') ? new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT) : null;
    $amountWords = $fmt ? $fmt->format($ttcAmount) : number_format($ttcAmount, 2, ',', ' ');
@endphp
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @php
                    $logoPath = public_path('uis-logo.png');
                    $logoData = '';
                    $hasGd = extension_loaded('gd');
                    if ($hasGd && file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($hasGd && $logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="UIS Logo" style="height: 115px; width: auto;">
                @else
                    <div style="font-size: 48px; font-weight: bold; color: #153b63; font-style: italic;">UIS</div>
                    <div style="font-size: 10px; color: #153b63;">UNIVERSAL INVEST STRATEGY</div>
                @endif
            </td>
            <td class="info-cell">
                <div class="company-name">Universal Invest Strategy.SARL</div>
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
                <div class="under-italic">Date de facture: {{ optional($invoice->date)->format('d/m/Y') }}</div>
            </td>
            <td class="dest-cell">
                <div class="under-italic">DESTINATAIRE:</div>
                <div class="recipient-box">
                    <div class="recipient-name">{{ strtoupper($clientName) }}</div>
                    @if($invoice->contract->client->company?->ice)
                        <div>ICE: {{ $invoice->contract->client->company->ice }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="invoice-title">
        Facture No{{ preg_replace('/[^0-9]/', '', $invoice->invoice_number) ?: $invoice->invoice_number }}
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
                <td style="height: 120px;">{{ $serviceLabel }}</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($htAmount, 2, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($htAmount, 2, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="label">TOTAL HT</td>
                <td class="value">{{ number_format($htAmount, 2, ',', ' ') }}</td>
            </tr>
            <tr>
                <td class="label">TVA 20%</td>
                <td class="value">{{ number_format($vatAmount, 2, ',', ' ') }}</td>
            </tr>
            <tr class="grand-total">
                <td class="label">Total TTC</td>
                <td class="value">{{ number_format($ttcAmount, 2, ',', ' ') }}</td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    <div class="amount-words">
        Arrêter la présente facture à la somme de {{ ucfirst($amountWords) }} DHS ,00 dhs
    </div>

    <div class="footer-line"></div>
    <div class="footer-content">
        Angle Rue al AARAR et av Lalla Yacout 3eme étage Appartement 8 ICE: 002752348000050<br>
        Tél: 0707040170-Email: contact@uivstrategy.ma RC: 496151-patente: 34102034-IF: 50137892-CNSS: 2507310
    </div>
</body>
</html>
