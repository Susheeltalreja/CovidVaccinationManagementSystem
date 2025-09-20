@extends('layouts.app')

@section('title', 'Patient Login - COVID Vaccination System')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Patient Login
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('patient.login.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Don't have an account? 
                        <a href="{{ route('patient.register') }}" class="text-decoration-none">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
