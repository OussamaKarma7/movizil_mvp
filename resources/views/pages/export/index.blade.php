@extends('layouts.app')

@section('title', 'Exporter les Données')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        
        <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background-color: #e0e7ff; color: #4338ca;">
            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
            <div>
                <strong>Centre d'Exportation</strong><br>
                <span class="small">Exportez les données de votre plateforme en toute sécurité. Assurez-vous de stocker les fichiers téléchargés dans des répertoires conformes.</span>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-file-excel text-success me-2"></i> Données Clients & Contrats</h5>
                <small class="text-secondary">Exportez des feuilles de calcul XLSX structurées adaptées au CRM ou à une analyse approfondie.</small>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row g-4">
                    
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 text-center hover-shadow transition-all bg-light">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fa-solid fa-users fs-3 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Registre des Clients</h5>
                            <p class="text-secondary small mb-4">Extraction complète de tous les clients enregistrés, incluant les détails ICE, RC et IF.</p>
                            <a href="{{ route('export.clients') }}" class="btn btn-primary w-100 fw-medium">
                                <i class="fa-solid fa-download me-2"></i> Exporter Clients
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 text-center hover-shadow transition-all bg-light">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fa-solid fa-file-contract fs-3 text-success"></i>
                            </div>
                            <h5 class="fw-bold">Contrats Actifs</h5>
                            <p class="text-secondary small mb-4">Une liste complète des contrats avec leurs dates de début/fin et leur valeur.</p>
                            <a href="{{ route('export.contracts') }}" class="btn btn-success w-100 fw-medium">
                                <i class="fa-solid fa-download me-2"></i> Exporter Contrats
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-file-lines text-secondary me-2"></i> Données Comptables & Journal</h5>
                <small class="text-secondary">Exportez vos écritures pour les importer dans votre logiciel de comptabilité ou pour une analyse Excel.</small>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 bg-light h-100">
                            <h6 class="fw-bold mb-2">Format SAGE 100 (.txt)</h6>
                            <p class="text-secondary small mb-4">Export optimisé sans en-tête (pure data), comptes sur 8 chiffres, séparateur point-virgule. Indispensable pour l'import Sage.</p>
                            <a href="{{ route('export.invoices.txt') }}" class="btn btn-dark w-100 fw-medium">
                                <i class="fa-solid fa-file-export me-2"></i> Exporter pour SAGE
                            </a>
                            <div class="mt-2 text-center">
                                <small class="text-muted"><i class="fa-solid fa-circle-info"></i> Utilisez un modèle (.ema) matching dans Sage.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 bg-light h-100">
                            <h6 class="fw-bold mb-2">Format EXCEL (.csv)</h6>
                            <p class="text-secondary small mb-4">Export lisible pour analyse interne ou pour import manuel si le format Sage échoue.</p>
                            <a href="{{ route('export.journal.excel') }}" class="btn btn-outline-dark w-100 fw-medium">
                                <i class="fa-solid fa-file-excel text-success me-2"></i> Journal vers Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
