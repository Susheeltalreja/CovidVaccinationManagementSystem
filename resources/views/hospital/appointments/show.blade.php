@extends('layouts.hospital')

@section('title', 'Appointment Details - Hospital')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Appointment Details
                        </h4>
                        <a href="{{ route('hospital.appointments') }}" class="btn btn-light btn-sm">
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
                                        <label class="form-label text-muted">Patient Notes</label>
                                        <p class="form-control-plaintext">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3">Patient Information</h5>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Patient Name</label>
                                    <p class="form-control-plaintext fw-bold">{{ $appointment->patient->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <p class="form-control-plaintext">{{ $appointment->patient->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p class="form-control-plaintext">{{ $appointment->patient->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Age</label>
                                    <p class="form-control-plaintext">{{ $appointment->patient->age ?? 'Not calculated' }}
                                        years</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Gender</label>
                                    <p class="form-control-plaintext">
                                        {{ ucfirst($appointment->patient->gender ?? 'Not specified') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Location</label>
                                    <p class="form-control-plaintext">
                                        {{ $appointment->patient->location ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Test Results / Vaccination Records -->
                        @if ($appointment->type == 'covid_test')
                            @if ($appointment->covidTestResult)
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
                            @else
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3">COVID-19 Test Result</h5>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No test result has been recorded yet.
                                            <button type="button" class="btn btn-sm btn-primary ms-2"
                                                data-bs-toggle="modal" data-bs-target="#covidTestModal">
                                                <i class="fas fa-plus me-1"></i>
                                                Add Test Result
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if ($appointment->type == 'vaccination')
                            @if ($appointment->vaccinationRecord)
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
                            @else
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3">Vaccination Record</h5>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No vaccination record has been created yet.
                                            <button type="button" class="btn btn-sm btn-primary ms-2"
                                                data-bs-toggle="modal" data-bs-target="#vaccinationModal">
                                                <i class="fas fa-plus me-1"></i>
                                                Add Vaccination Record
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-4">
                                @if ($appointment->status == 'pending')
                                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                        data-bs-target="#statusModal">
                                        <i class="fas fa-check me-2"></i>
                                        Update Status
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-check me-2"></i>
                                        Status Updated
                                    </button>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($appointment->type == 'covid_test' && !$appointment->covidTestResult)
                                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                        data-bs-target="#covidTestModal">
                                        <i class="fas fa-virus me-2"></i>
                                        Add Test Result
                                    </button>
                                @elseif($appointment->type == 'vaccination' && !$appointment->vaccinationRecord)
                                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal"
                                        data-bs-target="#vaccinationModal">
                                        <i class="fas fa-syringe me-2"></i>
                                        Add Vaccination Record
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-check me-2"></i>
                                        Record Added
                                    </button>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('hospital.appointments') }}" class="btn btn-primary w-100">
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

    <!-- Status Update Modal -->
    @if ($appointment->status == 'pending')
        <div class="modal fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Appointment Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('hospital.appointments.update-status', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">New Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                    placeholder="Add any additional notes about the status update"></textarea>
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

    <!-- COVID Test Result Modal -->
    @if ($appointment->type == 'covid_test' && !$appointment->covidTestResult)
        <div class="modal fade" id="covidTestModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add COVID-19 Test Result</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('hospital.appointments.covid-test', $appointment->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="result" class="form-label">Test Result *</label>
                                <select class="form-select @error('result') is-invalid @enderror" id="result"
                                    name="result" required>
                                    <option value="">Select Result</option>
                                    <option value="positive">Positive</option>
                                    <option value="negative">Negative</option>
                                    <option value="inconclusive">Inconclusive</option>
                                </select>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="test_date" class="form-label">Test Date *</label>
                                <input type="date" class="form-control @error('test_date') is-invalid @enderror"
                                    id="test_date" name="test_date" value="{{ date('Y-m-d') }}" required>
                                @error('test_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                    placeholder="Add any additional notes about the test result"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Add Test Result</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Vaccination Record Modal -->
    @if ($appointment->type == 'vaccination' && !$appointment->vaccinationRecord)
        <div class="modal fade" id="vaccinationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vaccination Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('hospital.appointments.vaccination', $appointment->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="vaccine_id" class="form-label">Vaccine *</label>
                                <select class="form-select @error('vaccine_id') is-invalid @enderror" id="vaccine_id"
                                    name="vaccine_id" required>
                                    <option value="">Select Vaccine</option>
                                    @foreach ($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }} -
                                            {{ $vaccine->description }}</option>
                                    @endforeach
                                </select>
                                @error('vaccine_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="dose_number" class="form-label">Dose Number *</label>
                                <select class="form-select @error('dose_number') is-invalid @enderror" id="dose_number"
                                    name="dose_number" required>
                                    <option value="">Select Dose</option>
                                    <option value="1">1st Dose</option>
                                    <option value="2">2nd Dose</option>
                                    <option value="3">3rd Dose</option>
                                    <option value="booster">Booster</option>
                                </select>
                                @error('dose_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="vaccination_date" class="form-label">Vaccination Date *</label>
                                <input type="date" class="form-control @error('vaccination_date') is-invalid @enderror"
                                    id="vaccination_date" name="vaccination_date" value="{{ date('Y-m-d') }}" required>
                                @error('vaccination_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                    placeholder="Add any additional notes about the vaccination"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Add Vaccination Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
