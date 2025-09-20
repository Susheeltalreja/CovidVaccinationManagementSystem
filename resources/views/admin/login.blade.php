@extends('layouts.app')

@section('title', 'Admin Login - COVID Vaccination System')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        Admin Login
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.login.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <strong>Demo Credentials:</strong><br>
                            Email: admin@covidvaccination.com<br>
                            Password: admin123
                        </small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
