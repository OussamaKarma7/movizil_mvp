@extends('layouts.app')

@section('title', 'Demande de Contrat de Domiciliation')

@push('styles')
<style>
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #e2e8f0;
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        background: var(--bg-color);
        padding: 0 15px;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 600;
        color: #94a3b8;
        transition: all 0.3s;
    }
    .step-item.active .step-circle {
        border-color: var(--primary-color);
        background-color: var(--primary-color);
        color: #fff;
    }
    .step-item.completed .step-circle {
        border-color: #10b981;
        background-color: #10b981;
        color: #fff;
    }
    .step-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .step-item.active .step-title {
        color: var(--primary-color);
    }
    
    .form-section {
        display: none;
    }
    .form-section.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-label.required::after {
        content: " *";
        color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            @if(session('success'))
            <div class="alert alert-success fw-bold p-4 shadow-sm mb-4">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            @endif

            <div class="card border-0 shadow-lg p-4 custom-glass">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: var(--primary-color);">UNIVERSAL INVEST STRATEGY</h2>
                    <p class="text-muted">Demande de Contrat de Domiciliation</p>
                </div>

                <!-- Stepper -->
                <div class="step-indicator px-4">
                    <div class="step-item active" id="indicator-1">
                        <div class="step-circle">1</div>
                        <div class="step-title">Informations Personnelles</div>
                    </div>
                    <div class="step-item" id="indicator-2" style="display:none;">
                        <div class="step-circle">2</div>
                        <div class="step-title">L'Entreprise</div>
                    </div>
                    <div class="step-item" id="indicator-3">
                        <div class="step-circle">3</div>
                        <div class="step-title">Le Contrat</div>
                    </div>
                </div>

                <hr class="mb-4 opacity-10">

                <!-- Form -->
                <form id="contractForm" action="{{ route('public.demande.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-section active" id="step-1">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-user me-2 text-primary"></i> Vos Informations</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required fw-bold" style="color: var(--primary-color);">Vous êtes ?</label>
                                <div class="d-flex gap-4 p-3 rounded" style="background: rgba(30, 41, 59, 0.03); border: 1px solid #e2e8f0;">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="client_type" id="type_individual" value="individual" checked onchange="toggleCompany(false)">
                                        <label class="form-check-label fw-bold" for="type_individual">Un Particulier</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="client_type" id="type_company" value="company" onchange="toggleCompany(true)">
                                        <label class="form-check-label fw-bold" for="type_company">Une Entreprise</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Prénom</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nom</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">CIN</label>
                                <input type="text" class="form-control" name="cin" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Téléphone</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Adresse</label>
                                <textarea class="form-control" name="address" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary px-4 fw-bold" onclick="proceedFromStep1()">Suivant <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 2: Company Information -->
                    <div class="form-section" id="step-2">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-building me-2" style="color: #10b981;"></i> Informations de l'Entreprise</h5>
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Nom de l'Entreprise (Raison sociale)</label>
                                <input type="text" class="form-control company-input" name="company_name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">ICE</label>
                                <input type="text" class="form-control company-input" name="ice">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">RC</label>
                                <input type="text" class="form-control company-input" name="rc">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">IF</label>
                                <input type="text" class="form-control company-input" name="if">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-light px-4 border" onclick="prevStep(1)"><i class="fa-solid fa-arrow-left me-2"></i> Précédent</button>
                            <button type="button" class="btn btn-primary px-4 fw-bold" onclick="nextStep(3)">Suivant <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Contract Information & Uploads -->
                    <div class="form-section" id="step-3">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-file-contract me-2" style="color: #f59e0b;"></i> Détails du Contrat</h5>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Type de Contrat</label>
                                <select class="form-select" name="contract_type" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="Domiciliation">Domiciliation Juridique</option>
                                    <option value="Basic Accounting">Tenue de Comptabilité</option>
                                    <option value="Consulting">Conseil Juridique</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Durée (Mois)</label>
                                <select class="form-select" id="durationSelect" name="duration" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="1">1 Mois</option>
                                    <option value="3">3 Mois</option>
                                    <option value="6">6 Mois</option>
                                    <option value="12">12 Mois (1 An)</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Date de Début</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            
                            <!-- Documents Upload -->
                            <h6 class="fw-bold mt-4 border-bottom pb-2">Documents (Optionnels)</h6>
                            <div class="col-md-6 mb-3">
                            <label class="form-label">Copie CIN</label>
                            <input class="form-control" type="file" name="cin_file">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Certificat Négatif</label>
                            <input class="form-control" type="file" name="certificat_file">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-light px-4 border" onclick="goBackFromStep3()"><i class="fa-solid fa-arrow-left me-2"></i> Précédent</button>
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i class="fa-solid fa-paper-plane me-2"></i> Soumettre la Demande</button>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    let isCompanyClient = false;

    function toggleCompany(isCompany) {
        isCompanyClient = isCompany;
        const indicator2 = document.getElementById('indicator-2');
        const companyInputs = document.querySelectorAll('.company-input');
        
        if (isCompany) {
            indicator2.style.display = 'block';
            companyInputs.forEach(el => el.setAttribute('required', 'required'));
        } else {
            indicator2.style.display = 'none';
            companyInputs.forEach(el => el.removeAttribute('required'));
        }
    }

    function proceedFromStep1() {
        if (!validateStep(1)) return;
        if (isCompanyClient) {
            nextStep(2);
        } else {
            // Skip directly to 3
            updateStepView(1, 3);
        }
    }

    function goBackFromStep3() {
        if (isCompanyClient) {
            prevStep(2);
        } else {
            // Go back directly to 1
            updateStepView(3, 1);
        }
    }

    function nextStep(step) {
        if(!validateStep(currentStep)) return;
        updateStepView(currentStep, step);
    }

    function prevStep(step) {
        updateStepView(currentStep, step, true);
    }

    function updateStepView(fromStep, toStep, isBack = false) {
        document.getElementById(`step-${fromStep}`).classList.remove('active');
        
        if (isBack) {
            document.getElementById(`indicator-${fromStep}`).classList.remove('active', 'completed');
            document.getElementById(`indicator-${toStep}`).classList.add('active');
            document.getElementById(`indicator-${toStep}`).classList.remove('completed');
        } else {
            document.getElementById(`indicator-${fromStep}`).classList.remove('active');
            document.getElementById(`indicator-${fromStep}`).classList.add('completed');
            document.getElementById(`indicator-${toStep}`).classList.add('active');
        }
        
        currentStep = toStep;
        document.getElementById(`step-${currentStep}`).classList.add('active');
    }

    function validateStep(step) {
        let isValid = true;
        document.querySelectorAll(`#step-${step} [required]`).forEach(input => {
            if(!input.value) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        if(!isValid) alert('Veuillez remplir tous les champs obligatoires.');
        return isValid;
    }
</script>
@endpush
