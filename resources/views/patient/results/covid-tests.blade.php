@extends('layouts.patient')

@section('title', 'COVID-19 Test Results - Patient')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-virus me-2"></i>
                        COVID-19 Test Results
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
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
                                    <h5 class="card-title">{{ $totalTests }}</h5>
                                    <p class="card-text">Total Tests</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $negativeTests }}</h5>
                                    <p class="card-text">Negative</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $positiveTests }}</h5>
                                    <p class="card-text">Positive</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $inconclusiveTests }}</h5>
                                    <p class="card-text">Inconclusive</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($covidTestResults->count() > 0)
                        <!-- Results Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Test Date</th>
                                        <th>Hospital</th>
                                        <th>Result</th>
                                        <th>Test Date</th>
                                        <th>Updated On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($covidTestResults as $result)
                                        <tr>
                                            <td>
                                                <strong>#{{ $result->appointment->id }}</strong><br>
                                                <small class="text-muted">{{ $result->appointment->appointment_date->format('M d, Y g:i A') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $result->hospital->name }}</strong><br>
                                                <small class="text-muted">{{ $result->hospital->location ?? 'Location not specified' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $resultColors = [
                                                        'positive' => 'danger',
                                                        'negative' => 'success',
                                                        'inconclusive' => 'warning'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $resultColors[$result->result] }} fs-6">
                                                    {{ ucfirst($result->result) }}
                                                </span>
                                            </td>
                                            <td>{{ $result->test_date->format('M d, Y') }}</td>
                                            <td>{{ $result->updated_at->format('M d, Y g:i A') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#resultModal{{ $result->id }}">
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
                            {{ $covidTestResults->links() }}
                        </div>
                    @else
                        <!-- No Results Message -->
                        <div class="text-center py-5">
                            <i class="fas fa-virus fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No COVID-19 Test Results Found</h5>
                            <p class="text-muted">You haven't taken any COVID-19 tests yet.</p>
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

<!-- Result Detail Modals -->
@foreach($covidTestResults as $result)
<div class="modal fade" id="resultModal{{ $result->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">COVID-19 Test Result Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Test Information</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted">Appointment ID</label>
                            <p class="form-control-plaintext fw-bold">#{{ $result->appointment->id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Appointment Date</label>
                            <p class="form-control-plaintext">{{ $result->appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Test Date</label>
                            <p class="form-control-plaintext">{{ $result->test_date->format('F d, Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Result</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $resultColors[$result->result] }} fs-6">
                                    {{ ucfirst($result->result) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Hospital Information</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted">Hospital Name</label>
                            <p class="form-control-plaintext fw-bold">{{ $result->hospital->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="form-control-plaintext">{{ $result->hospital->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="form-control-plaintext">{{ $result->hospital->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Location</label>
                            <p class="form-control-plaintext">{{ $result->hospital->location ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    @if($result->notes)
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                            <p class="form-control-plaintext">{{ $result->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('patient.appointments.show', $result->appointment->id) }}" class="btn btn-primary">
                    <i class="fas fa-calendar me-1"></i>
                    View Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
