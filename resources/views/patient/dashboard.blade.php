@extends('layouts.patient')

@section('title', 'Patient Dashboard - COVID Vaccination System')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Welcome, {{ $patient->name }}!
                </h1>
                <a href="{{ route('patient.hospitals.search') }}" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>
                    Find Hospital
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
                    <i class="bi bi-shield-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $patient->age }}</h3>
                    <p class="mb-0">Age</p>
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
                            <a href="{{ route('patient.hospitals.search') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search me-2"></i>
                                Find Hospital
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar me-2"></i>
                                My Appointments
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('patient.results.covid-tests') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-virus me-2"></i>
                                COVID Test Results
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('patient.results.vaccinations') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-syringe me-2"></i>
                                Vaccination Records
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('patient.profile') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user-circle me-2"></i>
                                My Profile
                            </a>
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
                        Patient Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted mb-1">Name</p>
                            <p class="fw-bold">{{ $patient->name }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Email</p>
                            <p class="fw-bold">{{ $patient->email }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Phone</p>
                            <p class="fw-bold">{{ $patient->phone }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Location</p>
                            <p class="fw-bold">{{ $patient->location }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('patient.profile') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Upcoming Appointments
                    </h5>
                    <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if ($upcomingAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital</th>
                                        <th>Type</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->hospital->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->type == 'covid_test' ? 'warning' : 'success' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->status == 'approved' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('patient.appointments.show', $appointment->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No upcoming appointments</h5>
                            <p class="text-muted">Book your first appointment to get started.</p>
                            <a href="{{ route('patient.hospitals.search') }}" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>
                                Find Hospital
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
