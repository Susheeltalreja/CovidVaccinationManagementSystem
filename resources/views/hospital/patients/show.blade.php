@extends('layouts.hospital')

@section('title', 'Patient Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Patient Details</h2>
        <a href="{{ route('hospital.patients') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>
</div>

            <!-- Patient Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $patient->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $patient->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $patient->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Age:</strong></td>
                                    <td>{{ $patient->age ?? 'N/A' }} years</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ ucfirst($patient->gender) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $patient->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $patient->city }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State:</strong></td>
                                    <td>{{ $patient->state }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments History -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Appointment History</h5>
                </div>
                <div class="card-body">
                    @if($patient->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->appointments->where('hospital_id', session('hospital_id')) as $appointment)
                                        <tr>
                                            <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $appointment->type === 'covid_test' ? 'bg-warning' : 'bg-info' }}">
                                                    {{ $appointment->type === 'covid_test' ? 'COVID Test' : 'Vaccination' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($appointment->status === 'pending') bg-warning
                                                    @elseif($appointment->status === 'approved') bg-primary
                                                    @elseif($appointment->status === 'completed') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->notes ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('hospital.appointments.show', $appointment->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No appointments found for this patient at your hospital.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- COVID Test Results -->
            @if($patient->covidTestResults->where('hospital_id', session('hospital_id'))->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-virus"></i> COVID Test Results</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Test Date</th>
                                    <th>Result</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->covidTestResults->where('hospital_id', session('hospital_id')) as $result)
                                    <tr>
                                        <td>{{ $result->test_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($result->result === 'positive') bg-danger
                                                @elseif($result->result === 'negative') bg-success
                                                @else bg-warning
                                                @endif">
                                                {{ ucfirst($result->result) }}
                                            </span>
                                        </td>
                                        <td>{{ $result->notes ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Vaccination Records -->
            @if($patient->vaccinationRecords->where('hospital_id', session('hospital_id'))->count() > 0)
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-syringe"></i> Vaccination Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vaccination Date</th>
                                    <th>Vaccine</th>
                                    <th>Dose</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->vaccinationRecords->where('hospital_id', session('hospital_id')) as $record)
                                    <tr>
                                        <td>{{ $record->vaccination_date->format('M d, Y') }}</td>
                                        <td>{{ $record->vaccine->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $record->getDoseTypeAttribute() }}
                                            </span>
                                        </td>
                                        <td>{{ $record->notes ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
@endsection
