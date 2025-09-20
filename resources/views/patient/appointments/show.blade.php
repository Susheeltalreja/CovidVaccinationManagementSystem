@extends('layouts.patient')

@section('title', 'Appointment Details - Patient')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Appointment Details
                        </h4>
                        <a href="{{ route('patient.appointments') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Appointments
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Appointment Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3">Appointment Information</h5>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Appointment ID</label>
                                    <p class="form-control-plaintext fw-bold">#{{ $appointment->id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Type</label>
                                    <p class="form-control-plaintext">
                                        <span
                                            class="badge bg-{{ $appointment->type == 'covid_test' ? 'warning' : 'info' }}">
                                            {{ $appointment->type == 'covid_test' ? 'COVID-19 Test' : 'Vaccination' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date & Time</label>
                                    <p class="form-control-plaintext">
                                        {{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <p class="form-control-plaintext">
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
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created On</label>
                                    <p class="form-control-plaintext">
                                        {{ $appointment->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                                @if ($appointment->notes)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Notes</label>
                                        <p class="form-control-plaintext">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3">Hospital Information</h5>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Hospital Name</label>
                                    <p class="form-control-plaintext fw-bold">{{ $appointment->hospital->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p class="form-control-plaintext">{{ $appointment->hospital->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <p class="form-control-plaintext">{{ $appointment->hospital->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Location</label>
                                    <p class="form-control-plaintext">
                                        {{ $appointment->hospital->location ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Address</label>
                                    <p class="form-control-plaintext">
                                        {{ $appointment->hospital->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Test Results / Vaccination Records -->
                        @if ($appointment->type == 'covid_test' && $appointment->covidTestResult)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">COVID-19 Test Result</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted">Result</label>
                                                    <p class="form-control-plaintext">
                                                        @php
                                                            $resultColors = [
                                                                'positive' => 'danger',
                                                                'negative' => 'success',
                                                                'inconclusive' => 'warning',
                                                            ];
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $resultColors[$appointment->covidTestResult->result] }}">
                                                            {{ ucfirst($appointment->covidTestResult->result) }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted">Test Date</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $appointment->covidTestResult->test_date->format('F d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted">Updated On</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $appointment->covidTestResult->updated_at->format('F d, Y \a\t g:i A') }}
                                                    </p>
                                                </div>
                                                @if ($appointment->covidTestResult->notes)
                                                    <div class="col-12">
                                                        <label class="form-label text-muted">Notes</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $appointment->covidTestResult->notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($appointment->type == 'vaccination' && $appointment->vaccinationRecord)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Vaccination Record</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label text-muted">Vaccine</label>
                                                    <p class="form-control-plaintext fw-bold">
                                                        {{ $appointment->vaccinationRecord->vaccine->name }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-muted">Dose Number</label>
                                                    <p class="form-control-plaintext">
                                                        <span
                                                            class="badge bg-info">{{ $appointment->vaccinationRecord->dose_number }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-muted">Vaccination Date</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $appointment->vaccinationRecord->vaccination_date->format('F d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-muted">Updated On</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $appointment->vaccinationRecord->updated_at->format('F d, Y \a\t g:i A') }}
                                                    </p>
                                                </div>
                                                @if ($appointment->vaccinationRecord->notes)
                                                    <div class="col-12">
                                                        <label class="form-label text-muted">Notes</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $appointment->vaccinationRecord->notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                @if ($appointment->status == 'pending')
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#cancelAppointmentModal">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel Appointment
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times me-2"></i>
                                        Cannot Cancel
                                    </button>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('patient.appointments') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-list me-2"></i>
                                    Back to Appointments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Appointment Modal -->
    @if ($appointment->status == 'pending')
        <div class="modal fade" id="cancelAppointmentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Are you sure you want to cancel this appointment?</p>
                            <p class="text-muted">
                                <strong>Appointment:</strong>
                                {{ $appointment->type == 'covid_test' ? 'COVID-19 Test' : 'Vaccination' }}<br>
                                <strong>Date:</strong>
                                {{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}<br>
                                <strong>Hospital:</strong> {{ $appointment->hospital->name }}
                            </p>
                            <p class="text-danger"><small>This action cannot be undone.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep
                                Appointment</button>
                            <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
