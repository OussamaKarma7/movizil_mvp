@extends('layouts.app')

@section('title', 'Factures')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row g-4">
        
        <!-- Invoices List -->
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Factures Récentes</h6>
                    <form action="{{ route('invoices.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payee</option>
                        </select>
                        <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-secondary"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">Filtrer</button>
                    </form>
                </div>
                <div class="px-3 py-2 border-bottom bg-light small">
                    Total: <strong>{{ $counts['all'] ?? 0 }}</strong> |
                    En attente: <strong>{{ $counts['pending'] ?? 0 }}</strong> |
                    Payees: <strong>{{ $counts['paid'] ?? 0 }}</strong>
                </div>
                <div class="p-0 table-responsive border-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>N° Facture</th>
                                <th>Client / Date</th>
                                <th class="text-end">Montant</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr style="cursor: pointer;" onclick="selectInvoice(this, '{{ addslashes($invoice->invoice_number) }}', '{{ addslashes($invoice->contract->client->company->company_name ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name)) }}', '{{ addslashes($invoice->contract->type) }}', {{ $invoice->amount }}, '{{ $invoice->date }}', '{{ addslashes($invoice->contract->client->address) }}', '{{ addslashes($invoice->contract->client->company->ice ?? '') }}', '{{ route('invoices.pdf', $invoice->id) }}', '{{ optional($invoice->contract->start_date)->format('d/m/Y') }}', '{{ optional($invoice->contract->end_date)->format('d/m/Y') }}', {{ $invoice->id }}, '{{ route('invoices.sendEmail', $invoice->id) }}')">
                                <td class="fw-bold text-primary">{{ $invoice->invoice_number }}</td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $invoice->contract->client->company->company_name ?? ($invoice->contract->client->first_name . ' ' . $invoice->contract->client->last_name) }}</div>
                                    <div class="small text-secondary">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</div>
                                </td>
                                <td class="text-end fw-bold">{{ number_format($invoice->amount, 2) }} MAD</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Payée</span>
                                    @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">En attente</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('invoices.status', $invoice->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $invoice->status === 'paid' ? 'pending' : 'paid' }}">
                                        <button type="submit" class="btn btn-sm {{ $invoice->status === 'paid' ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $invoice->status === 'paid' ? 'Remettre attente' : 'Marquer payee' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Aucune facture trouvée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>

        <!-- Invoice Preview UI -->
        <div class="col-xl-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center p-3">
                    <h6 class="mb-0 fw-bold">Aperçu de la Facture</h6>
                    <div class="d-flex gap-2">
                        <a href="#" id="download-btn" class="btn btn-sm btn-primary px-3 rounded-pill disabled">
                            <i class="fa-solid fa-download me-2"></i> Télécharger PDF
                        </a>
                        <form id="send-email-form" action="#" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" id="send-email-btn" class="btn btn-sm btn-success px-3 rounded-pill disabled" 
                                onclick="return confirm('Envoyer la facture par email au client ?')"
                                {{ 'disabled' }}>
                                <i class="fa-solid fa-envelope me-2"></i> Envoyer par Email
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-4 bg-light" style="overflow-y: auto;">
                    <!-- Placeholder -->
                    <div id="invoice-placeholder" class="text-center py-5">
                        <i class="fa-solid fa-file-invoice text-secondary opacity-25 mb-3" style="font-size: 4rem;"></i>
                        <p class="text-secondary">Sélectionnez une facture pour voir l'aperçu</p>
                    </div>

                    <!-- ===== INVOICE PREVIEW ===== -->
                    <div id="invoice-preview" class="d-none mx-auto bg-white" style="
                        max-width: 820px;
                        font-family: 'Times New Roman', Times, serif;
                        color: #153b63;
                        border: 1px solid #ddd;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
                        padding: 40px 50px 120px 50px;
                        position: relative;
                        min-height: 1100px;
                        box-sizing: border-box;
                    ">
                        <!-- HEADER: Logo left, Company right -->
                        <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:40px;">
                            <tr>
                                <td style="width:45%; vertical-align:top;">
                                    <img src="{{ asset('uis-logo.png') }}" alt="UIS Logo" style="height:110px; width:auto; display:block;">
                                </td>
                                <td style="width:55%; text-align:right; vertical-align:top; padding-top:8px;">
                                    <div style="font-size:24px; font-weight:bold; font-style:italic; margin-bottom:4px; color:#153b63;">Universal Invest Strategy.<span style="font-size:15px;">SARL</span></div>
                                    <div style="font-size:12px; line-height:1.5; color:#153b63;">
                                        RUE EL AARAR ET BD LALLA YACOUT<br>
                                        212522273011<br>
                                        contact@uivstrategy.ma
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- META: Date left, Destinataire right -->
                        <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:10px;">
                            <tr>
                                <td style="width:50%; vertical-align:top; padding-top:5px;">
                                    <span style="text-decoration:underline; font-style:italic; font-size:13px; color:#153b63;">Date de facture: <span id="preview-date-top">-</span></span>
                                </td>
                                <td style="width:50%; text-align:right; vertical-align:top;">
                                    <div style="text-decoration:underline; font-style:italic; font-size:13px; font-weight:bold; color:#153b63; text-align:right;">DESTINATAIRE:</div>
                                    <div id="preview-client-name" style="font-size:15px; font-weight:bold; color:#000; text-align:right; margin-top:10px;">-</div>
                                    <div id="preview-client-details" style="font-size:12px; font-weight:bold; color:#000; text-align:right;"></div>
                                </td>
                            </tr>
                        </table>

                        <!-- INVOICE TITLE -->
                        <div style="font-size:30px; font-weight:bold; font-style:italic; color:#153b63; margin-top:35px; margin-bottom:15px;">
                            Facture N°<span id="preview-inv-number">-</span>
                        </div>

                        <!-- MAIN TABLE -->
                        <table style="width:100%; border-collapse:collapse; border:1px solid #f2994a;">
                            <thead>
                                <tr style="background-color:#f2994a;">
                                    <th style="color:white; text-align:center; padding:10px 8px; font-size:13px; font-weight:bold; border:1px solid #f2994a; width:50%;">DÉSIGNATION</th>
                                    <th style="color:white; text-align:center; padding:10px 8px; font-size:13px; font-weight:bold; border:1px solid #f2994a; width:15%;">QUANTITÉ</th>
                                    <th style="color:white; text-align:center; padding:10px 8px; font-size:13px; font-weight:bold; border:1px solid #f2994a; width:15%;">PRIX</th>
                                    <th style="color:white; text-align:center; padding:10px 8px; font-size:13px; font-weight:bold; border:1px solid #f2994a; width:20%;">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="preview-service" style="padding:30px 12px; border:1px solid #f2994a; color:#000; font-size:13px; vertical-align:middle; min-height:80px;">-</td>
                                    <td style="padding:30px 8px; border:1px solid #f2994a; color:#000; font-size:13px; text-align:center; vertical-align:middle;">1</td>
                                    <td id="preview-unit" style="padding:30px 8px; border:1px solid #f2994a; color:#000; font-size:13px; text-align:right; vertical-align:middle;">0,00</td>
                                    <td id="preview-amount" style="padding:30px 8px; border:1px solid #f2994a; color:#000; font-size:13px; text-align:right; vertical-align:middle;">0,00</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- TOTALS -->
                        <table style="width:45%; border-collapse:collapse; border:1px solid #f2994a; margin-left:auto; margin-top:15px;">
                            <tr>
                                <td style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; font-style:italic; text-align:right; color:#000; font-size:13px;">TOTAL HT</td>
                                <td id="preview-subtotal" style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; text-align:right; color:#000; font-size:13px; width:40%;">0,00</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; font-style:italic; text-align:right; color:#000; font-size:13px;">TVA 20%</td>
                                <td id="preview-vat" style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; text-align:right; color:#000; font-size:13px;">0,00</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; font-style:italic; text-align:right; color:#000; font-size:13px;">Total TTC</td>
                                <td id="preview-total" style="padding:8px 12px; border:1px solid #f2994a; font-weight:bold; text-align:right; color:#000; font-size:13px;">0,00</td>
                            </tr>
                        </table>

                        <!-- AMOUNT IN WORDS -->
                        <div style="text-align:center; font-weight:bold; text-decoration:underline; color:#000; font-size:13px; margin-top:50px;">
                            Arrêter la présente facture à la somme de <span id="preview-total-words">-</span> DHS ,00 dhs
                        </div>

                        <!-- FOOTER LINE -->
                        <div style="position:absolute; bottom:55px; left:50px; right:50px; height:2px; background-color:#f2994a;"></div>

                        <!-- FOOTER TEXT -->
                        <div style="position:absolute; bottom:15px; left:0; right:0; text-align:center; color:#153b63; font-size:9.5px; line-height:1.5; padding:0 50px;">
                            Angle Rue al AARAR et av Lalla Yacout 3eme étage Appartement 8 &nbsp; ICE:002752348000050<br>
                            Tél:0707040170-Email: contact@uivstrategy.ma &nbsp; RC: 496151-patente: 34102034-IF: 50137892-CNSS:2507310
                        </div>
                    </div>
                    <!-- ===== END INVOICE PREVIEW ===== -->
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectInvoice(rowEl, invNum, clientName, serviceDesc, amount, date, address, ice, pdfUrl, startDate, endDate, invoiceId, emailUrl) {
        document.getElementById('invoice-placeholder').classList.add('d-none');
        document.getElementById('invoice-preview').classList.remove('d-none');
        
        document.getElementById('preview-inv-number').innerText = invNum.replace(/[^0-9]/g, '') || invNum;
        document.getElementById('preview-client-name').innerText = clientName;
        document.getElementById('preview-service').innerText = (serviceDesc || '') + (startDate ? ' du ' + startDate : '') + (endDate ? ' au ' + endDate : '');
        document.getElementById('preview-date-top').innerText = date;
        document.getElementById('preview-client-details').innerHTML = (ice ? 'ICE: ' + ice + '<br>' : '');
        
        const vat = amount * 0.2;
        const total = amount + vat;
        let formattedAmount = amount.toLocaleString('fr-FR', { minimumFractionDigits: 2 });
        let formattedVat = vat.toLocaleString('fr-FR', { minimumFractionDigits: 2 });
        let formattedTotal = total.toLocaleString('fr-FR', { minimumFractionDigits: 2 });
        document.getElementById('preview-unit').innerText = formattedAmount;
        document.getElementById('preview-amount').innerText = formattedAmount;
        document.getElementById('preview-subtotal').innerText = formattedAmount;
        document.getElementById('preview-vat').innerText = formattedVat;
        document.getElementById('preview-total').innerText = formattedTotal;
        
        // Simple mock for amount in words in JS for preview
        document.getElementById('preview-total-words').innerText = formattedTotal;
        
        // Wire download button
        const downloadBtn = document.getElementById('download-btn');
        downloadBtn.href = pdfUrl;
        downloadBtn.classList.remove('disabled');

        // Wire send email button & form
        const emailForm = document.getElementById('send-email-form');
        const emailBtn = document.getElementById('send-email-btn');
        emailForm.action = emailUrl;
        emailBtn.removeAttribute('disabled');
        emailBtn.classList.remove('disabled');
        
        // Highlight selected row
        document.querySelectorAll('tbody tr').forEach(el => el.classList.remove('bg-light'));
        rowEl.classList.add('bg-light');
    }
</script>
@endpush
