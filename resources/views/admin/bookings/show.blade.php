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
                <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>
                        Patient Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Name:</strong></td>
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
                            <td><strong>Date of Birth:</strong></td>
                            <td>{{ $appointment->patient->date_of_birth ? $appointment->patient->date_of_birth->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Age:</strong></td>
                            <td>{{ $appointment->patient->age ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>
                                <span
                                    class="badge bg-{{ $appointment->patient->gender === 'male' ? 'primary' : ($appointment->patient->gender === 'female' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($appointment->patient->gender) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Location:</strong></td>
                            <td>{{ $appointment->patient->location ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>{{ $appointment->patient->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $appointment->patient->is_active ? 'success' : 'danger' }}">
                                    {{ $appointment->patient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Registered:</strong></td>
                            <td>{{ $appointment->patient->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        Hospital Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Name:</strong></td>
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
                            <td><strong>Address:</strong></td>
                            <td>{{ $appointment->hospital->address ?? 'N/A' }}</td>
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
                        <tr>
                            <td><strong>Active:</strong></td>
                            <td>
                                <span class="badge bg-{{ $appointment->hospital->is_active ? 'success' : 'danger' }}">
                                    {{ $appointment->hospital->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Registered:</strong></td>
                            <td>{{ $appointment->hospital->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Appointment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Type:</strong></td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $appointment->type === 'covid_test' ? 'info' : 'success' }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Appointment Date:</strong></td>
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
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Created:</strong></td>
                                    <td>{{ $appointment->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $appointment->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COVID Test Result -->
    @if ($appointment->covidTestResult)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-virus me-2"></i>
                            COVID Test Result
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Result:</strong></td>
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
                                    <tr>
                                        <td><strong>Notes:</strong></td>
                                        <td>{{ $appointment->covidTestResult->notes ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Created:</strong></td>
                                        <td>{{ $appointment->covidTestResult->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $appointment->covidTestResult->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Vaccination Record -->
    @if ($appointment->vaccinationRecord)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check me-2"></i>
                            Vaccination Record
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Vaccine:</strong></td>
                                        <td>{{ $appointment->vaccinationRecord->vaccine->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dose Number:</strong></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ ucfirst($appointment->vaccinationRecord->dose_number) }} Dose
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Vaccination Date:</strong></td>
                                        <td>{{ $appointment->vaccinationRecord->vaccination_date->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Notes:</strong></td>
                                        <td>{{ $appointment->vaccinationRecord->notes ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Vaccine Status:</strong></td>
                                        <td>
                                            @if ($appointment->vaccinationRecord->vaccine->status === 'available')
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Unavailable</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Quantity:</strong></td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $appointment->vaccinationRecord->vaccine->stock_quantity > 0 ? 'info' : 'warning' }}">
                                                {{ $appointment->vaccinationRecord->vaccine->stock_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $appointment->vaccinationRecord->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $appointment->vaccinationRecord->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Patient Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Patient Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-calendar-check display-6 text-primary mb-2"></i>
                                <h4 class="fw-bold">{{ $appointment->patient->appointments->count() }}</h4>
                                <p class="mb-0 text-muted">Total Appointments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-virus display-6 text-info mb-2"></i>
                                <h4 class="fw-bold">{{ $appointment->patient->covidTestResults->count() }}</h4>
                                <p class="mb-0 text-muted">COVID Tests</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-shield-check display-6 text-success mb-2"></i>
                                <h4 class="fw-bold">{{ $appointment->patient->vaccinationRecords->count() }}</h4>
                                <p class="mb-0 text-muted">Vaccinations</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-check-circle display-6 text-success mb-2"></i>
                                <h4 class="fw-bold">
                                    {{ $appointment->patient->appointments->where('status', 'completed')->count() }}</h4>
                                <p class="mb-0 text-muted">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
