@extends('layouts.app')

@section('title', 'Importer des Données')

@push('styles')
<style>
    .dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background-color: #f8fafc;
        padding: 50px 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .dropzone:hover {
        border-color: var(--primary-color);
        background-color: #eef2ff;
    }
    .dropzone-icon {
        font-size: 4rem;
        color: #94a3b8;
        margin-bottom: 20px;
    }
    .dropzone:hover .dropzone-icon {
        color: var(--primary-color);
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Upload Area -->
    <div class="col-xl-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Importation de Données</h5>
                <small class="text-secondary">Téléchargez des formats Excel (.xlsx) ou Texte (.txt) pour synchroniser les enregistrements externes.</small>
            </div>
            <div class="card-body p-4">
                
                <form id="importForm" action="{{ route('import.data') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Type de Données</label>
                        <select class="form-select" id="dataType" name="data_type">
                            <option value="clients_excel">Registre Clients (Excel)</option>
                            <option value="accounting_txt">Journal Comptable (TXT)</option>
                        </select>
                    </div>

                    <div class="dropzone" id="fileDropzone" onclick="document.getElementById('fileInput').click()">
                        <i class="fa-solid fa-file-arrow-up dropzone-icon"></i>
                        <h5 class="fw-bold text-dark mb-2">Glissez-déposez le fichier ici</h5>
                        <p class="text-secondary small mb-0">ou cliquez pour parcourir votre ordinateur</p>
                        <p class="text-secondary small mt-2 fw-medium">Taille max : 5Mo</p>
                    </div>
                    <input type="file" name="import_file" id="fileInput" class="d-none" onchange="handleFileSelect(event)">
                    
                    <div id="fileInfo" class="mt-3 p-3 bg-light rounded border d-none d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center overflow-hidden">
                            <i class="fa-solid fa-file-excel text-success fs-4 me-3 flex-shrink-0"></i>
                            <div class="text-truncate">
                                <strong class="d-block mb-0 text-truncate" id="fileName">data.xlsx</strong>
                                <small class="text-secondary">Prêt pour le traitement</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-circle-xmark text-danger ms-2" style="cursor: pointer;" onclick="clearFile()"></i>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4 fw-medium" id="processBtn" disabled>
                        <i class="fa-solid fa-gear me-2"></i> Traiter & Importer
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Preview Area -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">Aperçu en Direct</h5>
                    <small class="text-secondary">Vérifiez les données mappées avant de confirmer les modifications</small>
                </div>
                <span class="badge bg-secondary" id="statusBadge">En attente de téléchargement</span>
            </div>
            <div class="card-body p-0">
                
                <div id="emptyState" class="h-100 d-flex flex-column align-items-center justify-content-center p-5 text-center" style="min-height: 400px;">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/folder-4452033-3708579.png" alt="Vide" width="150" class="mb-3 opacity-50">
                    <h5 class="text-secondary fw-bold">Aucune donnée chargée</h5>
                    <p class="text-secondary small">Téléchargez un fichier pour voir un aperçu des lignes analysées.</p>
                </div>

                <div id="previewTable" class="table-responsive d-none">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Nom de l'entreprise</th>
                                <th>Numéro ICE</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 text-secondary">1</td>
                                <td class="fw-medium text-dark">DataTech Innovations</td>
                                <td><span class="badge bg-light text-dark border">112233445566</span></td>
                                <td class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i> Valide</td>
                            </tr>
                            <tr>
                                <td class="px-4 text-secondary">2</td>
                                <td class="fw-medium text-dark">Global Imports SARL</td>
                                <td><span class="badge bg-light text-dark border">998877665544</span></td>
                                <td class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i> Valide</td>
                            </tr>
                            <tr class="table-danger">
                                <td class="px-4 text-secondary">3</td>
                                <td class="fw-medium text-dark">Omega Corp</td>
                                <td><span class="badge bg-light text-danger border border-danger">ICE Manquant</span></td>
                                <td class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Erreur</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="p-3 bg-light border-top text-end">
                        <button class="btn btn-success fw-medium px-4"><i class="fa-solid fa-database me-2"></i> Confirmer les enregistrements valides</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('fileName').innerText = file.name;
            document.getElementById('fileInfo').classList.remove('d-none');
            document.getElementById('processBtn').disabled = false;
            
            let icon = document.querySelector('#fileInfo .fa-solid');
            icon.className = 'fa-solid fs-4 me-3 flex-shrink-0';
            if(file.name.endsWith('.txt')) {
                icon.classList.add('fa-file-lines', 'text-secondary');
            } else {
                icon.classList.add('fa-file-excel', 'text-success');
            }
        }
    }

    function clearFile() {
        document.getElementById('fileInput').value = '';
        document.getElementById('fileInfo').classList.add('d-none');
        document.getElementById('processBtn').disabled = true;
        document.getElementById('emptyState').classList.remove('d-none');
        document.getElementById('previewTable').classList.add('d-none');
        document.getElementById('statusBadge').innerText = 'En attente de téléchargement';
        document.getElementById('statusBadge').className = 'badge bg-secondary';
    }

    function simulateProcessing() {
        const btn = document.getElementById('processBtn');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Analyse en cours...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-solid fa-gear me-2"></i> Traiter & Importer';
            // Show preview table
            document.getElementById('emptyState').classList.add('d-none');
            document.getElementById('previewTable').classList.remove('d-none');
            
            document.getElementById('statusBadge').innerText = '2 Valides, 1 Erreur';
            document.getElementById('statusBadge').className = 'badge bg-warning text-dark';
        }, 1200);
    }
</script>
@endpush
