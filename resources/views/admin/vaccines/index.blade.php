@extends('layouts.admin')

@section('title', 'Manage Vaccines - Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Manage Vaccines
                </h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                        <i class="bi bi-plus me-2"></i>
                        Add Vaccine
                    </button>
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
                    <i class="bi bi-shield-check display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalVaccines }}</h3>
                    <p class="mb-0">Total Vaccines</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-2 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $availableVaccines }}</h3>
                    <p class="mb-0">Available</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-3 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $unavailableVaccines }}</h3>
                    <p class="mb-0">Unavailable</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card-4 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-box display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalStock }}</h3>
                    <p class="mb-0">Total Stock</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccines Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Vaccine List
                    </h5>
                </div>
                <div class="card-body">
                    @if ($vaccines->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vaccine Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Stock Quantity</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vaccines as $vaccine)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-shield-check me-2"></i>
                                                    {{ $vaccine->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ Str::limit($vaccine->description, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($vaccine->status === 'available')
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Unavailable</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $vaccine->stock_quantity > 0 ? 'info' : 'warning' }}">
                                                    {{ $vaccine->stock_quantity }}
                                                </span>
                                            </td>
                                            <td>{{ $vaccine->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVaccineModal{{ $vaccine->id }}">
                                                        <i class="bi bi-pencil">edit</i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#vaccineModal{{ $vaccine->id }}">
                                                        <i class="bi bi-eye">Details</i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $vaccines->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No vaccines found</h5>
                            <p class="text-muted">No vaccines have been added yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addVaccineModal">
                                <i class="bi bi-plus me-2"></i>
                                Add First Vaccine
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Vaccine Modal -->
    <div class="modal fade" id="addVaccineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New Vaccine
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.vaccines.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Vaccine Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                <option value="">Select Status</option>
                                <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>
                                    Unavailable</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                min="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus me-2"></i>
                            Add Vaccine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vaccine Modals -->
    @foreach ($vaccines as $vaccine)
        <div class="modal fade" id="editVaccineModal{{ $vaccine->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Vaccine
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.vaccines.update', $vaccine->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name_{{ $vaccine->id }}" class="form-label">Vaccine Name</label>
                                <input type="text" class="form-control" id="edit_name_{{ $vaccine->id }}"
                                    name="name" value="{{ $vaccine->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_description_{{ $vaccine->id }}" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description_{{ $vaccine->id }}" name="description" rows="3"
                                    required>{{ $vaccine->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_status_{{ $vaccine->id }}" class="form-label">Status</label>
                                <select class="form-select" id="edit_status_{{ $vaccine->id }}" name="status"
                                    required>
                                    <option value="available" {{ $vaccine->status === 'available' ? 'selected' : '' }}>
                                        Available</option>
                                    <option value="unavailable"
                                        {{ $vaccine->status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_stock_quantity_{{ $vaccine->id }}" class="form-label">Stock
                                    Quantity</label>
                                <input type="number" class="form-control" id="edit_stock_quantity_{{ $vaccine->id }}"
                                    name="stock_quantity" value="{{ $vaccine->stock_quantity }}" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>
                                Update Vaccine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Vaccine Detail Modal -->
        <div class="modal fade" id="vaccineModal{{ $vaccine->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-shield-check me-2"></i>
                            Vaccine Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Vaccine Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $vaccine->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $vaccine->description }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if ($vaccine->status === 'available')
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Unavailable</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Quantity:</strong></td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $vaccine->stock_quantity > 0 ? 'info' : 'warning' }}">
                                                {{ $vaccine->stock_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Statistics</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Total Vaccinations:</strong></td>
                                        <td>{{ $vaccine->vaccinationRecords->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $vaccine->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $vaccine->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editVaccineModal{{ $vaccine->id }}" data-bs-dismiss="modal">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Vaccine
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
