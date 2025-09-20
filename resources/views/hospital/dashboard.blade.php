@extends('layouts.hospital')

@section('title', 'Hospital Dashboard - COVID Vaccination System')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-building me-2"></i>
                    Welcome, {{ $hospital->name }}!
                </h1>
                <a href="{{ route('hospital.appointments') }}" class="btn btn-primary">
                    <i class="bi bi-calendar-check me-2"></i>
                    View Appointments
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalAppointments }}</h3>
                    <p class="mb-0">Total Appointments</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $pendingAppointments }}</h3>
                    <p class="mb-0">Pending Appointments</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $completedAppointments }}</h3>
                    <p class="mb-0">Completed Appointments</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalPatients }}</h3>
                    <p class="mb-0">Total Patients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('hospital.appointments') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar me-2"></i>
                                Appointments
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('hospital.patients') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-users me-2"></i>
                                Patient List
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('hospital.profile') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-hospital me-2"></i>
                                Hospital Profile
                            </a>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('hospital.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Hospital Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted mb-1">Name</p>
                            <p class="fw-bold">{{ $hospital->name }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Email</p>
                            <p class="fw-bold">{{ $hospital->email }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Phone</p>
                            <p class="fw-bold">{{ $hospital->phone }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Location</p>
                            <p class="fw-bold">{{ $hospital->location }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-{{ $hospital->status == 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($hospital->status) }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('hospital.profile') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card-body">
        @if($appointments->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-calendar-check display-4 text-muted mb-3"></i>
                <h5 class="text-muted">No appointments yet</h5>
                <p class="text-muted">Appointments will appear here once patients book with your hospital.</p>
                <a href="{{ route('hospital.appointments') }}" class="btn btn-primary">
                    <i class="bi bi-calendar me-2"></i>
                    View Appointments
                </a>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach($appointments as $appointment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $appointment->patient->name }}</strong><br>
                            <small>{{ $appointment->appointment_date->format('d M Y, h:i A') }}</small>
                        </div>
                        <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection