@extends('layouts.app')

@section('title', 'Home - COVID Vaccination System')

@section('content')
    <!-- Hero Section -->
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-primary mb-4">
                COVID Vaccination System
            </h1>
            <p class="lead mb-4">
                A comprehensive online platform for COVID-19 testing and vaccination management.
                Connect patients with hospitals for seamless appointment booking and result tracking.
            </p>
            <div class="d-flex gap-3">
                <a href="{{ route('user.type') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-right me-2"></i>
                    Get Started
                </a>
                <a href="#features" class="btn btn-outline-primary btn-lg">
                    Learn More
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="text-center">
                <i class="bi bi-shield-check display-1 text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">System Features</h2>
                <p class="text-muted">Comprehensive COVID-19 management solution</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-check display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Patient Management</h5>
                        <p class="card-text text-muted">
                            Easy registration and profile management for patients.
                            Book appointments and track test results.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-building display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Hospital Integration</h5>
                        <p class="card-text text-muted">
                            Hospitals can manage appointments, update test results,
                            and track vaccination records efficiently.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-graph-up display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Admin Dashboard</h5>
                        <p class="card-text text-muted">
                            Comprehensive reporting and analytics.
                            Manage hospitals, vaccines, and generate detailed reports.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Types Section -->
    <div class="py-5 bg-light">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Choose Your Role</h2>
                <p class="text-muted">Select your user type to get started</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Patient</h5>
                        <p class="card-text text-muted mb-4">
                            Book COVID tests and vaccinations. Track your results and appointments.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('patient.register') }}" class="btn btn-primary">
                                Register as Patient
                            </a>
                            <a href="{{ route('patient.login') }}" class="btn btn-outline-primary">
                                Patient Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-building display-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Hospital</h5>
                        <p class="card-text text-muted mb-4">
                            Manage patient appointments and update test/vaccination records.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('hospital.register') }}" class="btn btn-primary">
                                Register Hospital
                            </a>
                            <a href="{{ route('hospital.login') }}" class="btn btn-outline-primary">
                                Hospital Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-check display-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Administrator</h5>
                        <p class="card-text text-muted mb-4">
                            System administration, reports, and hospital management.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.login') }}" class="btn btn-primary">
                                Admin Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">System Statistics</h2>
                <p class="text-muted">Real-time data from our platform</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card stats-card border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-people display-4 mb-3"></i>
                        <h3 class="fw-bold">1000+</h3>
                        <p class="mb-0">Registered Patients</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card-2 border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-building display-4 mb-3"></i>
                        <h3 class="fw-bold">50+</h3>
                        <p class="mb-0">Partner Hospitals</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card-3 border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check display-4 mb-3"></i>
                        <h3 class="fw-bold">5000+</h3>
                        <p class="mb-0">Appointments</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card-4 border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check display-4 mb-3"></i>
                        <h3 class="fw-bold">3000+</h3>
                        <p class="mb-0">Vaccinations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
