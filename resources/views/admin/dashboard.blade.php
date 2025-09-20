@extends('layouts.admin')

@section('title', 'Admin Dashboard - COVID Vaccination System')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>
                Admin Dashboard
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.patients') }}" class="btn btn-outline-primary">
                    <i class="fas fa-users me-2"></i>
                    Manage Patients
                </a>
                <a href="{{ route('admin.hospitals') }}" class="btn btn-outline-primary">
                    <i class="fas fa-hospital me-2"></i>
                    Manage Hospitals
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-users display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalPatients }}</h3>
                    <p class="mb-0">Total Patients</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-hospital display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalHospitals }}</h3>
                    <p class="mb-0">Total Hospitals</p>
                    @if ($pendingHospitals > 0)
                        <small class="text-white-50">{{ $pendingHospitals }} pending approval</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalAppointments }}</h3>
                    <p class="mb-0">Total Appointments</p>
                    <small class="text-white-50">{{ $pendingAppointments }} pending</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-syringe display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalVaccines }}</h3>
                    <p class="mb-0">Vaccines Available</p>
                    <small class="text-white-50">{{ $availableVaccines }} active</small>
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
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('admin.patients') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>
                                Manage Patients
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.hospitals') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-hospital me-2"></i>
                                Manage Hospitals
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.reports') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-chart-line me-2"></i>
                                View Reports
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.vaccines') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-syringe me-2"></i>
                                Manage Vaccines
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
                        <i class="fas fa-chart-line me-2"></i>
                        System Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="display-6 text-success">{{ $completedAppointments }}</div>
                                <div class="text-muted">Completed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="display-6 text-warning">{{ $pendingAppointments }}</div>
                                <div class="text-muted">Pending</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="display-6 text-info">{{ $availableVaccines }}</div>
                                <div class="text-muted">Active Vaccines</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="display-6 text-danger">{{ $pendingHospitals }}</div>
                                <div class="text-muted">Pending Approval</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Recent Activity
                    </h5>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if ($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Hospital</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentAppointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-circle me-2"></i>
                                                    {{ $appointment->patient->name }}
                                                </div>
                                            </td>
                                            <td>{{ $appointment->hospital->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->type === 'covid_test' ? 'info' : 'success' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'approved' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No recent activity</h5>
                            <p class="text-muted">Activity will appear here as users interact with the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-heartbeat me-2"></i>
                        System Health
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success display-6 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Database</h6>
                                    <small class="text-muted">Connected</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success display-6 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Application</h6>
                                    <small class="text-muted">Running</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success display-6 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Security</h6>
                                    <small class="text-muted">Active</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success display-6 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Backup</h6>
                                    <small class="text-muted">Scheduled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
