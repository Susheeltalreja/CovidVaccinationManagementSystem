@extends('layouts.patient')

@section('title', 'Vaccination Records - Patient')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-syringe me-2"></i>
                            Vaccination Records
                        </h4>
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
                                        <h5 class="card-title">{{ $totalVaccinations }}</h5>
                                        <p class="card-text">Total Vaccinations</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $firstDoses }}</h5>
                                        <p class="card-text">First Doses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $secondDoses }}</h5>
                                        <p class="card-text">Second Doses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $boosterDoses }}</h5>
                                        <p class="card-text">Booster Doses</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($vaccinationRecords->count() > 0)
                            <!-- Records Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Appointment</th>
                                            <th>Hospital</th>
                                            <th>Vaccine</th>
                                            <th>Dose</th>
                                            <th>Vaccination Date</th>
                                            <th>Updated On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vaccinationRecords as $record)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $record->appointment->id }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $record->appointment->appointment_date->format('M d, Y g:i A') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $record->hospital->name }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $record->hospital->location ?? 'Location not specified' }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $record->vaccine->name }}</strong><br>
                                                    <small class="text-muted">{{ $record->vaccine->description }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $doseColors = [
                                                            '1' => 'success',
                                                            '2' => 'info',
                                                            '3' => 'warning',
                                                            'booster' => 'danger',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $doseColors[$record->dose_number] }} fs-6">
                                                        {{ ucfirst($record->dose_number) }}
                                                    </span>
                                                </td>
                                                <td>{{ $record->vaccination_date->format('M d, Y') }}</td>
                                                <td>{{ $record->updated_at->format('M d, Y g:i A') }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#vaccinationModal{{ $record->id }}">
                                                        <i class="fas fa-eye me-1"></i>
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $vaccinationRecords->links() }}
                            </div>
                        @else
                            <!-- No Records Message -->
                            <div class="text-center py-5">
                                <i class="fas fa-syringe fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Vaccination Records Found</h5>
                                <p class="text-muted">You haven't received any vaccinations yet.</p>
                                <a href="{{ route('patient.hospitals.search') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Find a Hospital
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccination Record Detail Modals -->
    @foreach ($vaccinationRecords as $record)
        <div class="modal fade" id="vaccinationModal{{ $record->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vaccination Record Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Vaccination Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Appointment ID</label>
                                    <p class="form-control-plaintext fw-bold">#{{ $record->appointment->id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Appointment Date</label>
                                    <p class="form-control-plaintext">
                                        {{ $record->appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Vaccination Date</label>
                                    <p class="form-control-plaintext">{{ $record->vaccination_date->format('F d, Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Dose Number</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-{{ $doseColors[$record->dose_number] }} fs-6">
                                            {{ ucfirst($record->dose_number) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Vaccine Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Vaccine Name</label>
                                    <p class="form-control-plaintext fw-bold">{{ $record->vaccine->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Description</label>
                                    <p class="form-control-plaintext">{{ $record->vaccine->description }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <p class="form-control-plaintext">
                                        <span
                                            class="badge bg-{{ $record->vaccine->status == 'available' ? 'success' : 'danger' }}">
                                            {{ ucfirst($record->vaccine->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Hospital Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Hospital Name</label>
                                    <p class="form-control-plaintext fw-bold">{{ $record->hospital->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p class="form-control-plaintext">{{ $record->hospital->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <p class="form-control-plaintext">{{ $record->hospital->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Location</label>
                                    <p class="form-control-plaintext">{{ $record->hospital->location ?? 'Not provided' }}
                                    </p>
                                </div>
                            </div>
                            @if ($record->notes)
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                                    <p class="form-control-plaintext">{{ $record->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('patient.appointments.show', $record->appointment->id) }}"
                            class="btn btn-primary">
                            <i class="fas fa-calendar me-1"></i>
                            View Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
