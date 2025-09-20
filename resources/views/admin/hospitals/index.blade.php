@extends('layouts.admin')

@section('title', 'Manage Hospitals - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-building me-2"></i>
                    Manage Hospitals
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
                    <i class="bi bi-building display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalHospitals }}</h3>
                    <p class="mb-0">Total Hospitals</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $approvedHospitals }}</h3>
                    <p class="mb-0">Approved</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $pendingHospitals }}</h3>
                    <p class="mb-0">Pending</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $rejectedHospitals }}</h3>
                    <p class="mb-0">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.hospitals') }}" class="row g-3">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Hospital name, email, or phone">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
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

    <!-- Hospitals Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Hospital List
                    </h5>
                </div>
                <div class="card-body">
                    @if ($hospitals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Appointments</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hospitals as $hospital)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building me-2"></i>
                                                    {{ $hospital->name }}
                                                </div>
                                            </td>
                                            <td>{{ $hospital->email }}</td>
                                            <td>{{ $hospital->phone }}</td>
                                            <td>{{ $hospital->location ?? 'N/A' }}</td>
                                            <td>
                                                @if ($hospital->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($hospital->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $hospital->appointments_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $hospital->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ($hospital->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $hospital->id }}">
                                                            <i class="bi bi-eye">
                                                                Approve
                                                            </i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $hospital->id }}">
                                                            <i class="bi bi-x">Reject</i>
                                                        </button>
                                                    @elseif($hospital->status === 'approved')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                            data-bs-toggle="modal" data-bs-target="#hospitalModal{{ $hospital->id }}">
                                                            <i class="bi bi-check">Check</i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $hospital->id }}">
                                                            <i class="bi bi-x">Reject</i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $hospital->id }}">
                                                            <i class="bi bi-eye">
                                                                Approve
                                                            </i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $hospitals->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-building display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hospitals found</h5>
                            <p class="text-muted">No hospitals match your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Hospital Detail Modals -->
    @foreach ($hospitals as $hospital)
        <div class="modal fade" id="hospitalModal{{ $hospital->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-building me-2"></i>
                            Hospital Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Hospital Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $hospital->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $hospital->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $hospital->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location:</strong></td>
                                        <td>{{ $hospital->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $hospital->address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Status & Statistics</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if ($hospital->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($hospital->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Active:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $hospital->is_active ? 'success' : 'danger' }}">
                                                {{ $hospital->is_active ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Appointments:</strong></td>
                                        <td>{{ $hospital->appointments->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registered:</strong></td>
                                        <td>{{ $hospital->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve Modal -->
        @if ($hospital->status === 'pending' || $hospital->status === 'approved' || $hospital->status === 'rejected')
            <div class="modal fade" id="approveModal{{ $hospital->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-check-circle me-2"></i>
                                Approve Hospital
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.hospitals.status', $hospital->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <div class="modal-body">
                                <p>Are you sure you want to approve <strong>{{ $hospital->name }}</strong>?</p>
                                <p class="text-muted">This will allow the hospital to start accepting appointments.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check me-2"></i>
                                    Approve Hospital
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $hospital->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-x-circle me-2"></i>
                                Reject Hospital
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.hospitals.status', $hospital->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <div class="modal-body">
                                <p>Are you sure you want to reject <strong>{{ $hospital->name }}</strong>?</p>
                                <p class="text-muted">This will prevent the hospital from accepting appointments.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x me-2"></i>
                                    Reject Hospital
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection