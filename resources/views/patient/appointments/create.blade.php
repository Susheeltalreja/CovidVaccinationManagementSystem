@extends('layouts.patient')

@section('title', 'Book Appointment - COVID Vaccination System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-calendar-plus me-2"></i>
                Book Appointment
            </h1>
            <a href="{{ route('patient.hospitals.show', $hospital->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Hospital
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Appointment Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('patient.hospitals.book.store', $hospital->id) }}" method="POST">
                    @csrf
                    
                    <!-- Hospital Information -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building display-6 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1">{{ $hospital->name }}</h6>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $hospital->location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Type -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Appointment Type *</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" 
                                           id="covid_test" value="covid_test" required>
                                    <label class="form-check-label" for="covid_test">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-virus display-6 text-warning me-3"></i>
                                            <div>
                                                <h6 class="mb-1">COVID-19 Test</h6>
                                                <p class="text-muted small mb-0">PCR or Rapid Antigen Test</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" 
                                           id="vaccination" value="vaccination" required>
                                    <label class="form-check-label" for="vaccination">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-check display-6 text-success me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Vaccination</h6>
                                                <p class="text-muted small mb-0">COVID-19 Vaccine</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Appointment Date & Time -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="appointment_date" class="form-label">Preferred Date *</label>
                            <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                   id="appointment_date" name="appointment_date" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                   value="{{ old('appointment_date') }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="appointment_time" class="form-label">Preferred Time *</label>
                            <select class="form-select @error('appointment_time') is-invalid @enderror" 
                                    id="appointment_time" name="appointment_time" required>
                                <option value="">Select Time</option>
                                <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="12:00" {{ old('appointment_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                <option value="17:00" {{ old('appointment_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                            </select>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Any special requirements or additional information...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">terms and conditions</a> 
                                and understand that this appointment is subject to hospital approval.
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Information Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Important Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="fw-bold">Before Your Appointment</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Bring a valid ID
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Arrive 15 minutes early
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Wear a face mask
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Follow social distancing
                        </li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">What to Expect</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-clock text-primary me-2"></i>
                            Appointment duration: 30-45 minutes
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-bell text-primary me-2"></i>
                            You'll receive confirmation via email
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-phone text-primary me-2"></i>
                            Hospital may contact you for changes
                        </li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> Appointments are subject to availability and hospital approval. 
                    You will be notified of any changes.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
