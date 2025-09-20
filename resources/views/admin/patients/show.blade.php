@extends('layouts.admin')

@section('title', 'Patient Details - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Patient Details
                </h1>
                <a href="{{ route('admin.patients') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Patients
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
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Name:</strong></td>
                            <td>{{ $patient->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $patient->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $patient->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Age:</strong></td>
                            <td>{{ $patient->age ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>
                                <span
                                    class="badge bg-{{ $patient->gender === 'male' ? 'primary' : ($patient->gender === 'female' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($patient->gender) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Location:</strong></td>
                            <td>{{ $patient->location ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>{{ $patient->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $patient->is_active ? 'success' : 'danger' }}">
                                    {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Registered:</strong></td>
                            <td>{{ $patient->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-calendar-check display-6 text-primary mb-2"></i>
                                <h4 class="fw-bold">{{ $patient->appointments->count() }}</h4>
                                <p class="mb-0 text-muted">Total Appointments</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-virus display-6 text-info mb-2"></i>
                                <h4 class="fw-bold">{{ $patient->covidTestResults->count() }}</h4>
                                <p class="mb-0 text-muted">COVID Tests</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-shield-check display-6 text-success mb-2"></i>
                                <h4 class="fw-bold">{{ $patient->vaccinationRecords->count() }}</h4>
                                <p class="mb-0 text-muted">Vaccinations</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-check-circle display-6 text-success mb-2"></i>
                                <h4 class="fw-bold">{{ $patient->appointments->where('status', 'completed')->count() }}
                                </h4>
                                <p class="mb-0 text-muted">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Appointments History
                    </h5>
                </div>
                <div class="card-body">
                    @if ($patient->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital</th>
                                        <th>Type</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patient->appointments as $appointment)
                                        <tr>
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
                                            <td>{{ $appointment->notes ?? 'N/A' }}</td>
                                            <td>{{ $appointment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No appointments found</h5>
                            <p class="text-muted">This patient has no appointment history.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- COVID Test Results -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-virus me-2"></i>
                        COVID Test Results
                    </h5>
                </div>
                <div class="card-body">
                    @if ($patient->covidTestResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital</th>
                                        <th>Result</th>
                                        <th>Test Date</th>
                                        <th>Notes</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patient->covidTestResults as $result)
                                        <tr>
                                            <td>{{ $result->hospital->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $result->result === 'positive' ? 'danger' : ($result->result === 'negative' ? 'success' : 'warning') }}">
                                                    {{ ucfirst($result->result) }}
                                                </span>
                                            </td>
                                            <td>{{ $result->test_date->format('M d, Y') }}</td>
                                            <td>{{ $result->notes ?? 'N/A' }}</td>
                                            <td>{{ $result->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-virus display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No COVID test results found</h5>
                            <p class="text-muted">This patient has no COVID test history.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccination Records -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        Vaccination Records
                    </h5>
                </div>
                <div class="card-body">
                    @if ($patient->vaccinationRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital</th>
                                        <th>Vaccine</th>
                                        <th>Dose</th>
                                        <th>Vaccination Date</th>
                                        <th>Notes</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patient->vaccinationRecords as $record)
                                        <tr>
                                            <td>{{ $record->hospital->name }}</td>
                                            <td>{{ $record->vaccine->name }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ ucfirst($record->dose_number) }} Dose
                                                </span>
                                            </td>
                                            <td>{{ $record->vaccination_date->format('M d, Y') }}</td>
                                            <td>{{ $record->notes ?? 'N/A' }}</td>
                                            <td>{{ $record->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No vaccination records found</h5>
                            <p class="text-muted">This patient has no vaccination history.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
