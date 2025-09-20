@extends('layouts.hospital')

@section('title', 'Appointments - Hospital')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Appointments
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal"
                                data-bs-target="#filterModal">
                                <i class="fas fa-filter me-1"></i>
                                Filter
                            </button>
                            <a href="{{ route('hospital.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $totalAppointments }}</h5>
                                        <p class="card-text">Total Appointments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $pendingAppointments }}</h5>
                                        <p class="card-text">Pending</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $completedAppointments }}</h5>
                                        <p class="card-text">Completed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $todayAppointments }}</h5>
                                        <p class="card-text">Today's Appointments</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($appointments->count() > 0)
                            <!-- Appointments Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Appointment ID</th>
                                            <th>Patient</th>
                                            <th>Type</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $appointment->id }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $appointment->patient->name }}</strong><br>
                                                    <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $appointment->type == 'covid_test' ? 'warning' : 'info' }}">
                                                        {{ $appointment->type == 'covid_test' ? 'COVID-19 Test' : 'Vaccination' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $appointment->appointment_date->format('g:i A') }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                            'completed' => 'primary',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColors[$appointment->status] }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $appointment->created_at->format('M d, Y g:i A') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('hospital.appointments.show', $appointment->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if ($appointment->status == 'pending')
                                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#statusModal{{ $appointment->id }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </div>
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
                            <!-- No Appointments Message -->
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Appointments Found</h5>
                                <p class="text-muted">No patients have booked appointments with your hospital yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Appointments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('hospital.appointments') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search by Patient Name</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Enter patient name">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="covid_test" {{ request('type') == 'covid_test' ? 'selected' : '' }}>COVID
                                    Test</option>
                                <option value="vaccination" {{ request('type') == 'vaccination' ? 'selected' : '' }}>
                                    Vaccination</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ request('date') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('hospital.appointments') }}" class="btn btn-secondary">Clear Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Update Modals -->
    @foreach ($appointments as $appointment)
        @if ($appointment->status == 'pending')
            <div class="modal fade" id="statusModal{{ $appointment->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Appointment Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('hospital.appointments.update-status', $appointment->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
                                <p><strong>Type:</strong>
                                    {{ $appointment->type == 'covid_test' ? 'COVID-19 Test' : 'Vaccination' }}</p>
                                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}
                                </p>

                                <div class="mb-3">
                                    <label for="status" class="form-label">New Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
