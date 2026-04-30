@extends('layouts.app')

@section('title', 'Renouvellement du Contrat')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-light btn-sm me-3">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h4 class="mb-0 fw-bold text-dark">Renouvellement du Contrat #{{ $contract->id }}</h4>
                </div>

                <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold" style="font-size: 0.7rem;">Prix du Renouvellement</div>
                            <div class="h4 mb-0 fw-bold" id="priceDisplay">... <small class="fs-6">MAD</small></div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2">
                            <i class="fa-solid fa-calculator fs-5"></i>
                        </div>
                    </div>
                </div>

                <form action="{{ route('contracts.storeRenewal', $contract->id) }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de début</label>
                            <input type="date" class="form-control" name="start_date" id="renewalStartDate"
                                   value="{{ $contract->end_date ? $contract->end_date->copy()->addDay()->format('Y-m-d') : now()->format('Y-m-d') }}" required onchange="calculateRenewalPrice()">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de fin</label>
                            <input type="date" class="form-control" name="end_date" id="renewalEndDate"
                                   value="{{ $contract->end_date ? $contract->end_date->copy()->addYear()->format('Y-m-d') : now()->addYear()->format('Y-m-d') }}" required onchange="calculateRenewalPrice()">
                        </div>
                        
                        <div class="col-12 d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-3 fw-bold">
                                <i class="fa-solid fa-sync-alt me-2"></i> Confirmer le Renouvellement
                            </button>
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
    function calculateRenewalPrice() {
        const start = document.getElementById('renewalStartDate').value;
        const end = document.getElementById('renewalEndDate').value;
        const priceDisplay = document.getElementById('priceDisplay');
        const rate = 165;
        
        if (start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            // Calculate months difference
            let months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
            months -= startDate.getMonth();
            months += endDate.getMonth();
            
            const totalMonths = Math.max(1, months);
            const totalPrice = totalMonths * rate;
            
            priceDisplay.innerHTML = totalPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' <small class="fs-6">MAD</small>';
        } else {
            priceDisplay.innerHTML = '0.00 <small class="fs-6">MAD</small>';
        }
    }
    
    // Initial calculation
    document.addEventListener('DOMContentLoaded', calculateRenewalPrice);
</script>
@endpush
