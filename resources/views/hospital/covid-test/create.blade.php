@extends('layouts.hospital')

@section('title', 'Record COVID Test Result')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Record COVID Test Result</h2>
        <a href="{{ route('hospital.appointments.show', $appointment->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Appointment
        </a>
    </div>
</div>

            <!-- Appointment Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
                            <p><strong>Phone:</strong> {{ $appointment->patient->phone }}</p>
                            <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Type:</strong> <span class="badge bg-warning">COVID Test</span></p>
                            <p><strong>Status:</strong> 
                                <span class="badge 
                                    @if($appointment->status === 'pending') bg-warning
                                    @elseif($appointment->status === 'approved') bg-primary
                                    @else bg-success
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>
                            <p><strong>Notes:</strong> {{ $appointment->notes ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COVID Test Result Form -->
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-virus"></i> COVID Test Result</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hospital.appointments.covid-test', $appointment->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="result" class="form-label">Test Result <span class="text-danger">*</span></label>
                                    <select class="form-select @error('result') is-invalid @enderror" id="result" name="result" required>
                                        <option value="">Select Result</option>
                                        <option value="positive" {{ old('result') === 'positive' ? 'selected' : '' }}>Positive</option>
                                        <option value="negative" {{ old('result') === 'negative' ? 'selected' : '' }}>Negative</option>
                                        <option value="inconclusive" {{ old('result') === 'inconclusive' ? 'selected' : '' }}>Inconclusive</option>
                                    </select>
                                    @error('result')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="test_date" class="form-label">Test Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('test_date') is-invalid @enderror" 
                                           id="test_date" name="test_date" value="{{ old('test_date', date('Y-m-d')) }}" required>
                                    @error('test_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes/Comments</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Enter any additional notes or comments about the test result...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Test Result
                                </button>
                                <a href="{{ route('hospital.appointments.show', $appointment->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection
