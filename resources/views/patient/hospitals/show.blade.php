@extends('layouts.patient')

@section('title', $hospital->name . ' - COVID Vaccination System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-building me-2"></i>
                {{ $hospital->name }}
            </h1>
            <a href="{{ route('patient.hospitals.search') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Search
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Hospital Information -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Hospital Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Hospital Name</label>
                        <p class="fw-bold">{{ $hospital->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Location</label>
                        <p class="fw-bold">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $hospital->location }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="fw-bold">
                            <i class="bi bi-telephone me-1"></i>
                            {{ $hospital->phone }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-bold">
                            <i class="bi bi-envelope me-1"></i>
                            {{ $hospital->email }}
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Full Address</label>
                        <p class="fw-bold">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $hospital->address }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Available -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Services Available
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0">
                                <i class="bi bi-virus display-6 text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">COVID-19 Testing</h6>
                                <p class="text-muted small mb-0">PCR and Rapid Antigen Tests</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0">
                                <i class="bi bi-shield-check display-6 text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Vaccination</h6>
                                <p class="text-muted small mb-0">COVID-19 Vaccines Available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Section -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Book Appointment
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bi bi-building display-4 text-primary mb-3"></i>
                    <h6>{{ $hospital->name }}</h6>
                    <p class="text-muted small">{{ $hospital->location }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('patient.hospitals.book', $hospital->id) }}" 
                       class="btn btn-primary btn-lg">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Book Appointment
                    </a>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Appointments are subject to availability
                        </small>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <h6 class="mb-3">Quick Info</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">Available</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Response Time</small>
                            <span class="fw-bold">24-48 hours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
