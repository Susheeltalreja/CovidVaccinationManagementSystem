@extends('layouts.patient')

@section('title', 'My Profile - Patient')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            My Profile
                        </h4>
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

                        <!-- Profile Information -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar mb-3">
                                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                                </div>
                                <h5 class="text-muted">{{ $patient->name }}</h5>
                                <span class="badge bg-success">Active Patient</span>
                            </div>
                            <div class="col-md-9">
                                <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <p class="form-control-plaintext">{{ $patient->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <p class="form-control-plaintext">{{ $patient->email }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Phone Number</label>
                                        <p class="form-control-plaintext">{{ $patient->phone }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Date of Birth</label>
                                        <p class="form-control-plaintext">
                                            {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'Not provided' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Gender</label>
                                        <p class="form-control-plaintext">{{ ucfirst($patient->gender ?? 'Not specified') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Age</label>
                                        <p class="form-control-plaintext">{{ $patient->age ?? 'Not calculated' }} years</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Location</label>
                                        <p class="form-control-plaintext">{{ $patient->location ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Address</label>
                                        <p class="form-control-plaintext">{{ $patient->address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Profile
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key me-2"></i>
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('patient.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" hidden class="form-control d-hidden @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $patient->email) }}" required>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $patient->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                    id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                    name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male"
                                        {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female"
                                        {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other"
                                        {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $patient->location) }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $patient->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('patient.profile.password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
