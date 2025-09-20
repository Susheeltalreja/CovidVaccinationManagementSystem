@extends('layouts.admin')

@section('title', 'Manage Patients - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-people me-2"></i>
                    Manage Patients
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
                    <i class="bi bi-people display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalPatients }}</h3>
                    <p class="mb-0">Total Patients</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $activePatients }}</h3>
                    <p class="mb-0">Active Patients</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-gender-male display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $malePatients }}</h3>
                    <p class="mb-0">Male Patients</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-gender-female display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $femalePatients }}</h3>
                    <p class="mb-0">Female Patients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.patients') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Name, email, or phone">
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Patient List
                    </h5>
                </div>
                <div class="card-body">
                    @if ($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Appointments</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $patient)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    {{ $patient->name }}
                                                </div>
                                            </td>
                                            <td>{{ $patient->email }}</td>
                                            <td>{{ $patient->phone }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $patient->gender === 'male' ? 'primary' : ($patient->gender === 'female' ? 'danger' : 'secondary') }}">
                                                    {{ ucfirst($patient->gender) }}
                                                </span>
                                            </td>
                                            <td>{{ $patient->age ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $patient->is_active ? 'success' : 'danger' }}">
                                                    {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $patient->appointments_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $patient->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#patientModal{{ $patient->id }}">
                                                    <i class="bi bi-eye">Details</i>
                                                </button>
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
                        <div class="text-center py-4">
                            <i class="bi bi-people display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No patients found</h5>
                            <p class="text-muted">No patients match your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Detail Modals -->
    @foreach ($patients as $patient)
        <div class="modal fade" id="patientModal{{ $patient->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-person-circle me-2"></i>
                            Patient Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Personal Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
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
                                        <td><strong>Gender:</strong></td>
                                        <td>{{ ucfirst($patient->gender) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $patient->age ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location:</strong></td>
                                        <td>{{ $patient->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $patient->address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Statistics</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Total Appointments:</strong></td>
                                        <td>{{ $patient->appointments->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>COVID Tests:</strong></td>
                                        <td>{{ $patient->covidTestResults->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Vaccinations:</strong></td>
                                        <td>{{ $patient->vaccinationRecords->count() }}</td>
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
                    <div class="modal-footer">
                        <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary">
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
