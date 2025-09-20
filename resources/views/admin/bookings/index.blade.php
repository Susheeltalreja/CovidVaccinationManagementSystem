@extends('layouts.admin')

@section('title', 'Booking Details - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Booking Details
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Dashboard
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
                    <h3 class="fw-bold">{{ $totalBookings }}</h3>
                    <p class="mb-0">Total Bookings</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $pendingBookings }}</h3>
                    <p class="mb-0">Pending</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $approvedBookings }}</h3>
                    <p class="mb-0">Approved</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check2-all display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $completedBookings }}</h3>
                    <p class="mb-0">Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>
                        Filter Bookings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="patient" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patient" name="patient"
                                value="{{ request('patient') }}" placeholder="Search patient name">
                        </div>
                        <div class="col-md-3">
                            <label for="hospital" class="form-label">Hospital Name</label>
                            <input type="text" class="form-control" id="hospital" name="hospital"
                                value="{{ request('hospital') }}" placeholder="Search hospital name">
                        </div>
                        <div class="col-md-2">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="covid_test" {{ request('type') === 'covid_test' ? 'selected' : '' }}>COVID
                                    Test</option>
                                <option value="vaccination" {{ request('type') === 'vaccination' ? 'selected' : '' }}>
                                    Vaccination</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ request('date') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>
                                Filter
                            </button>
                            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Booking List
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary">{{ $appointments->total() }} records</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Hospital</th>
                                        <th>Type</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                                        <small
                                                            class="text-muted">{{ $appointment->patient->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building me-2"></i>
                                                    {{ $appointment->hospital->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->type === 'covid_test' ? 'info' : 'success' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                            <td>
                                                @if ($appointment->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($appointment->status === 'approved')
                                                    <span class="badge bg-primary">Approved</span>
                                                @elseif($appointment->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#bookingModal{{ $appointment->id }}">
                                                    <i class="bi bi-eye">Check Appointment</i>
                                                </button>
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
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No bookings found</h5>
                            <p class="text-muted">No bookings match your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Detail Modals -->
    @foreach ($appointments as $appointment)
        <div class="modal fade" id="bookingModal{{ $appointment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-check me-2"></i>
                            Booking Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Patient Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $appointment->patient->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $appointment->patient->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $appointment->patient->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $appointment->patient->age ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gender:</strong></td>
                                        <td>{{ ucfirst($appointment->patient->gender) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Hospital Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $appointment->hospital->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $appointment->hospital->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $appointment->hospital->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location:</strong></td>
                                        <td>{{ $appointment->hospital->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if ($appointment->hospital->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($appointment->hospital->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Appointment Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $appointment->type === 'covid_test' ? 'info' : 'success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if ($appointment->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($appointment->status === 'approved')
                                                <span class="badge bg-primary">Approved</span>
                                            @elseif($appointment->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Notes:</strong></td>
                                        <td>{{ $appointment->notes ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Timestamps</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $appointment->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $appointment->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>

                                @if ($appointment->covidTestResult)
                                    <h6 class="mt-3">COVID Test Result</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Result:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $appointment->covidTestResult->result === 'positive' ? 'danger' : ($appointment->covidTestResult->result === 'negative' ? 'success' : 'warning') }}">
                                                    {{ ucfirst($appointment->covidTestResult->result) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Test Date:</strong></td>
                                            <td>{{ $appointment->covidTestResult->test_date->format('M d, Y') }}</td>
                                        </tr>
                                    </table>
                                @endif

                                @if ($appointment->vaccinationRecord)
                                    <h6 class="mt-3">Vaccination Record</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Vaccine:</strong></td>
                                            <td>{{ $appointment->vaccinationRecord->vaccine->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dose:</strong></td>
                                            <td>{{ ucfirst($appointment->vaccinationRecord->dose_number) }} Dose</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date:</strong></td>
                                            <td>{{ $appointment->vaccinationRecord->vaccination_date->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('admin.bookings.show', $appointment->id) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>
                            View Full Details
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
