@extends('layouts.patient')

@section('title', 'My Appointments - COVID Vaccination System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-calendar-check me-2"></i>
                My Appointments
            </h1>
            <a href="{{ route('patient.hospitals.search') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>
                Book New Appointment
            </a>
        </div>
    </div>
</div>

<!-- Appointments List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    Appointment History
                </h5>
            </div>
            <div class="card-body">
                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hospital</th>
                                    <th>Type</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->hospital->name }}</div>
                                                    <small class="text-muted">{{ $appointment->hospital->location }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->type == 'covid_test' ? 'warning' : 'success' }}">
                                                <i class="bi bi-{{ $appointment->type == 'covid_test' ? 'virus' : 'shield-check' }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_date->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'completed' => 'info'
                                                ];
                                                $statusIcons = [
                                                    'pending' => 'clock',
                                                    'approved' => 'check-circle',
                                                    'rejected' => 'x-circle',
                                                    'completed' => 'check-circle-fill'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$appointment->status] }}">
                                                <i class="bi bi-{{ $statusIcons[$appointment->status] }} me-1"></i>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $appointment->created_at->format('M d, Y') }}
                                            </small>
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">No appointments found</h5>
                        <p class="text-muted">You haven't booked any appointments yet.</p>
                        <a href="{{ route('patient.hospitals.search') }}" class="btn btn-primary">
                            <i class="bi bi-plus me-2"></i>
                            Book Your First Appointment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
@if($appointments->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Appointment Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-primary">{{ $appointments->where('status', 'pending')->count() }}</div>
                                <div class="text-muted">Pending</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-success">{{ $appointments->where('status', 'approved')->count() }}</div>
                                <div class="text-muted">Approved</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-info">{{ $appointments->where('status', 'completed')->count() }}</div>
                                <div class="text-muted">Completed</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-danger">{{ $appointments->where('status', 'rejected')->count() }}</div>
                                <div class="text-muted">Rejected</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
