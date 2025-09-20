@extends('layouts.app')

@section('title', 'Choose User Type - COVID Vaccination System')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-8">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary mb-3">Choose Your Role</h1>
            <p class="lead text-muted">Select your user type to access the COVID Vaccination System</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                        </div>
                        <h4 class="card-title mb-3">Patient</h4>
                        <p class="card-text text-muted mb-4">
                            Book COVID-19 tests and vaccinations. Track your appointments and view test results.
                        </p>
                        <div class="d-grid gap-3">
                            <a href="{{ route('patient.register') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>
                                Register as Patient
                            </a>
                            <a href="{{ route('patient.login') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Patient Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-building display-1 text-primary"></i>
                        </div>
                        <h4 class="card-title mb-3">Hospital</h4>
                        <p class="card-text text-muted mb-4">
                            Manage patient appointments, update test results, and track vaccination records.
                        </p>
                        <div class="d-grid gap-3">
                            <a href="{{ route('hospital.register') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-building-add me-2"></i>
                                Register Hospital
                            </a>
                            <a href="{{ route('hospital.login') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Hospital Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('home') }}" class="btn btn-link text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
