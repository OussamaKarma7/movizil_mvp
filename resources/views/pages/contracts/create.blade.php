@extends('layouts.app')

@section('title', 'Create New Contract')

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
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-sm p-4">
                
                <!-- Stepper -->
                <div class="step-indicator px-4">
                    <div class="step-item active" id="indicator-1">
                        <div class="step-circle">1</div>
                        <div class="step-title">Personal Info</div>
                    </div>
                    <div class="step-item" id="indicator-2">
                        <div class="step-circle">2</div>
                        <div class="step-title">Company Info</div>
                    </div>
                    <div class="step-item" id="indicator-3">
                        <div class="step-circle">3</div>
                        <div class="step-title">Contract Details</div>
                    </div>
                </div>

                    <!-- Price Preview -->
                    <div class="card bg-success text-white border-0 shadow-sm mb-4">
                        <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold" style="font-size: 0.7rem;">Prix de Création</div>
                                <div class="h4 mb-0 fw-bold" id="priceDisplay">800.00 <small class="fs-6">MAD</small></div>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-2">
                                <i class="fa-solid fa-tags fs-5"></i>
                            </div>
                        </div>
                    </div>

                <!-- Form -->
                <form id="contractForm" action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-section active" id="step-1">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-user me-2 text-primary"></i> Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">CIN</label>
                                <input type="text" class="form-control" name="cin" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Phone</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Address</label>
                                <textarea class="form-control" name="address" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary px-4" onclick="nextStep(2)">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 2: Company Information -->
                    <div class="form-section" id="step-2">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-building me-2" style="color: #10b981;"></i> Company Information</h5>
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Company Name</label>
                                <input type="text" class="form-control" name="company_name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">ICE</label>
                                <input type="text" class="form-control" name="ice" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">RC</label>
                                <input type="text" class="form-control" name="rc" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">IF</label>
                                <input type="text" class="form-control" name="if" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-light px-4 border" onclick="prevStep(1)"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                            <button type="button" class="btn btn-primary px-4" onclick="nextStep(3)">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Contract Information & Uploads -->
                    <div class="form-section" id="step-3">
                        <h5 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-file-contract me-2" style="color: #f59e0b;"></i> Contract Settings</h5>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Contract Type</label>
                                <select class="form-select" name="contract_type" required>
                                    <option value="">Select Type...</option>
                                    <option value="Basic Accounting">Basic Accounting</option>
                                    <option value="Full Management">Full Management</option>
                                    <option value="Consulting">Consulting</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Date de début</label>
                                <input type="date" class="form-control" name="start_date" id="startDateInput" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Date de fin</label>
                                <input type="date" class="form-control" name="end_date" id="endDateInput" required>
                            </div>
                            
                            <!-- Documents Upload -->
                            
                            <!-- Documents Upload -->
                            <h6 class="fw-bold mt-2 border-bottom pb-2">Legal Documents (Required)</h6>
                            <div class="col-md-6 mb-3">
                            <label class="form-label required">CIN Copy</label>
                            <input class="form-control" type="file" name="cin_file" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Certificat Négatif</label>
                            <input class="form-control" type="file" name="certificat_file" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-light px-4 border" onclick="prevStep(2)"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i class="fa-solid fa-check me-2"></i> Generate Contract</button>
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

    function nextStep(step) {
        // Validate current step fields before proceeding (Simple simulation)
        let isValid = true;
        document.querySelectorAll(`#step-${currentStep} [required]`).forEach(input => {
            if(!input.value) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if(!isValid) {
            alert('Please fill all required fields.');
            return;
        }

        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`indicator-${currentStep}`).classList.add('completed');
        document.getElementById(`indicator-${currentStep}`).classList.remove('active');
        
        currentStep = step;
        
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`indicator-${currentStep}`).classList.add('active');
    }

    function prevStep(step) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`indicator-${currentStep}`).classList.remove('active');
        
        currentStep = step;
        
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`indicator-${currentStep}`).classList.remove('completed');
    }

    document.getElementById('startDateInput').addEventListener('change', updateCreationPrice);
    document.getElementById('endDateInput').addEventListener('change', updateCreationPrice);

    function updateCreationPrice() {
        const start = document.getElementById('startDateInput').value;
        const end = document.getElementById('endDateInput').value;
        const priceDisplay = document.getElementById('priceDisplay');
        const ratePerYear = 800;
        
        if (start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            // Calculate months difference
            let months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
            months -= startDate.getMonth();
            months += endDate.getMonth();
            
            const totalMonths = Math.max(1, months);
            const totalYears = Math.ceil(totalMonths / 12);
            const totalPrice = totalYears * ratePerYear;
            
            priceDisplay.innerHTML = totalPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' <small class="fs-6">MAD</small>';
        } else {
            priceDisplay.innerHTML = '800,00 <small class="fs-6">MAD</small>';
        }
    }

    // Initial calculation
    document.addEventListener('DOMContentLoaded', updateCreationPrice);
</script>
@endpush
