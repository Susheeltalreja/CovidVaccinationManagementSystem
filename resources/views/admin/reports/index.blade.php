@extends('layouts.admin')

@section('title', 'Reports - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports & Analytics
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.export') }}?{{ http_build_query(request()->all()) }}"
                        class="btn btn-success">
                        <i class="bi bi-download me-2"></i>
                        Export CSV
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalAppointments }}</h3>
                    <p class="mb-0">Total Appointments</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-virus display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $covidTestAppointments }}</h3>
                    <p class="mb-0">COVID Tests</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $vaccinationAppointments }}</h3>
                    <p class="mb-0">Vaccinations</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $completedAppointments }}</h3>
                    <p class="mb-0">Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>
                        Filter Reports
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="covid_test" {{ $type === 'covid_test' ? 'selected' : '' }}>COVID Test
                                </option>
                                <option value="vaccination" {{ $type === 'vaccination' ? 'selected' : '' }}>Vaccination
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="period" class="form-label">Time Period</label>
                            <select class="form-select" id="period" name="period">
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ $startDate }}" {{ $period !== 'custom' ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-2">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ $endDate }}" {{ $period !== 'custom' ? 'disabled' : '' }}>
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

    <!-- Reports Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Appointment Reports
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
                                                <span
                                                    class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'approved' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#appointmentModal{{ $appointment->id }}">
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
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-up display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No appointments found</h5>
                            <p class="text-muted">No appointments match your filter criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Detail Modals -->
    @foreach ($appointments as $appointment)
        <div class="modal fade" id="appointmentModal{{ $appointment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-check me-2"></i>
                            Appointment Details
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
                                            <span
                                                class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'approved' ? 'primary' : 'warning') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodSelect = document.getElementById('period');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function toggleDateInputs() {
                const isCustom = periodSelect.value === 'custom';
                startDateInput.disabled = !isCustom;
                endDateInput.disabled = !isCustom;
            }

            periodSelect.addEventListener('change', toggleDateInputs);
            toggleDateInputs();
        });
    </script>
@endpush
