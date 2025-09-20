@extends('layouts.hospital')

@section('title', 'Patient List - Hospital')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Patient List
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
                                        <h5 class="card-title">{{ $totalPatients }}</h5>
                                        <p class="card-text">Total Patients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $activePatients }}</h5>
                                        <p class="card-text">Active Patients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $covidTestPatients }}</h5>
                                        <p class="card-text">COVID Test Patients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $vaccinationPatients }}</h5>
                                        <p class="card-text">Vaccination Patients</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($patients->count() > 0)
                            <!-- Patients Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Age/Gender</th>
                                            <th>Location</th>
                                            <th>Appointments</th>
                                            <th>Last Visit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patients as $patient)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $patient->id }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $patient->name }}</strong><br>
                                                    <small class="text-muted">{{ $patient->email }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $patient->phone }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $patient->age ?? 'N/A' }} years</strong><br>
                                                    <small
                                                        class="text-muted">{{ ucfirst($patient->gender ?? 'Not specified') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $patient->location ?? 'Not provided' }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($patient->address, 30) ?? 'Address not provided' }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $appointmentCounts = $patient
                                                            ->appointments()
                                                            ->where('hospital_id', session('hospital_id'))
                                                            ->selectRaw('type, COUNT(*) as count')
                                                            ->groupBy('type')
                                                            ->pluck('count', 'type')
                                                            ->toArray();
                                                    @endphp
                                                    <div class="d-flex flex-column gap-1">
                                                        @if (isset($appointmentCounts['covid_test']))
                                                            <span
                                                                class="badge bg-warning">{{ $appointmentCounts['covid_test'] }}
                                                                COVID Tests</span>
                                                        @endif
                                                        @if (isset($appointmentCounts['vaccination']))
                                                            <span
                                                                class="badge bg-info">{{ $appointmentCounts['vaccination'] }}
                                                                Vaccinations</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $lastAppointment = $patient
                                                            ->appointments()
                                                            ->where('hospital_id', session('hospital_id'))
                                                            ->latest('appointment_date')
                                                            ->first();
                                                    @endphp
                                                    @if ($lastAppointment)
                                                        <strong>{{ $lastAppointment->appointment_date->format('M d, Y') }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $lastAppointment->type == 'covid_test' ? 'COVID Test' : 'Vaccination' }}</small>
                                                    @else
                                                        <span class="text-muted">No visits</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#patientModal{{ $patient->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="{{ route('hospital.appointments', ['patient_id' => $patient->id]) }}"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-calendar"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $patients->links() }}
                            </div>
                        @else
                            <!-- No Patients Message -->
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Patients Found</h5>
                                <p class="text-muted">No patients have made appointments with your hospital yet.</p>
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
                    <h5 class="modal-title">Filter Patients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('hospital.patients') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search by Name or Email</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Enter patient name or email">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointment_type" name="appointment_type">
                                <option value="">All Types</option>
                                <option value="covid_test"
                                    {{ request('appointment_type') == 'covid_test' ? 'selected' : '' }}>COVID Test</option>
                                <option value="vaccination"
                                    {{ request('appointment_type') == 'vaccination' ? 'selected' : '' }}>Vaccination
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('hospital.patients') }}" class="btn btn-secondary">Clear Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Patient Detail Modals -->
    @foreach ($patients as $patient)
        <div class="modal fade" id="patientModal{{ $patient->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patient Details - {{ $patient->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <p class="form-control-plaintext fw-bold">{{ $patient->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email Address</label>
                                    <p class="form-control-plaintext">{{ $patient->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone Number</label>
                                    <p class="form-control-plaintext">{{ $patient->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date of Birth</label>
                                    <p class="form-control-plaintext">
                                        {{ $patient->date_of_birth ? $patient->date_of_birth->format('F d, Y') : 'Not provided' }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Age</label>
                                    <p class="form-control-plaintext">{{ $patient->age ?? 'Not calculated' }} years</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Gender</label>
                                    <p class="form-control-plaintext">{{ ucfirst($patient->gender ?? 'Not specified') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Location Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Location</label>
                                    <p class="form-control-plaintext">{{ $patient->location ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Address</label>
                                    <p class="form-control-plaintext">{{ $patient->address ?? 'Not provided' }}</p>
                                </div>
                                <h6 class="border-bottom pb-2 mb-3 mt-4">Appointment History</h6>
                                @php
                                    $appointments = $patient
                                        ->appointments()
                                        ->where('hospital_id', session('hospital_id'))
                                        ->with(['covidTestResult', 'vaccinationRecord'])
                                        ->latest('appointment_date')
                                        ->take(5)
                                        ->get();
                                @endphp
                                @if ($appointments->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($appointments as $appointment)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $appointment->appointment_date->format('M d, Y g:i A') }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $appointment->type == 'covid_test' ? 'COVID Test' : 'Vaccination' }}</small>
                                                </div>
                                                <span
                                                    class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No appointments found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('hospital.appointments', ['patient_id' => $patient->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-calendar me-1"></i>
                            View Appointments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
