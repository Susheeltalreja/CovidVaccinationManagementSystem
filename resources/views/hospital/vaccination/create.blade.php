@extends('layouts.hospital')

@section('title', 'Record Vaccination')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Record Vaccination</h2>
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
                            <p><strong>Type:</strong> <span class="badge bg-info">Vaccination</span></p>
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

            <!-- Previous Vaccination History -->
            @if($appointment->patient->vaccinationRecords->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Previous Vaccination History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vaccine</th>
                                    <th>Dose</th>
                                    <th>Hospital</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointment->patient->vaccinationRecords as $record)
                                    <tr>
                                        <td>{{ $record->vaccination_date->format('M d, Y') }}</td>
                                        <td>{{ $record->vaccine->name ?? 'N/A' }}</td>
                                        <td>{{ $record->getDoseTypeAttribute() }}</td>
                                        <td>{{ $record->hospital->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Vaccination Form -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-syringe"></i> Record Vaccination</h5>
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

                    <form method="POST" action="{{ route('hospital.appointments.vaccination', $appointment->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccine_id" class="form-label">Vaccine <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vaccine_id') is-invalid @enderror" id="vaccine_id" name="vaccine_id" required>
                                        <option value="">Select Vaccine</option>
                                        @foreach($vaccines as $vaccine)
                                            <option value="{{ $vaccine->id }}" {{ old('vaccine_id') == $vaccine->id ? 'selected' : '' }}>
                                                {{ $vaccine->name }} 
                                                @if($vaccine->stock_quantity > 0)
                                                    ({{ $vaccine->stock_quantity }} available)
                                                @else
                                                    (Out of Stock)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vaccine_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dose_number" class="form-label">Dose Number <span class="text-danger">*</span></label>
                                    <select class="form-select @error('dose_number') is-invalid @enderror" id="dose_number" name="dose_number" required>
                                        <option value="">Select Dose</option>
                                        <option value="1" {{ old('dose_number') == '1' ? 'selected' : '' }}>First Dose</option>
                                        <option value="2" {{ old('dose_number') == '2' ? 'selected' : '' }}>Second Dose</option>
                                        <option value="3" {{ old('dose_number') == '3' ? 'selected' : '' }}>Third Dose</option>
                                        <option value="4" {{ old('dose_number') == '4' ? 'selected' : '' }}>Booster</option>
                                    </select>
                                    @error('dose_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccination_date" class="form-label">Vaccination Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('vaccination_date') is-invalid @enderror" 
                                           id="vaccination_date" name="vaccination_date" value="{{ old('vaccination_date', date('Y-m-d')) }}" required>
                                    @error('vaccination_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="next_dose_date" class="form-label">Next Dose Due Date</label>
                                    <input type="date" class="form-control @error('next_dose_date') is-invalid @enderror" 
                                           id="next_dose_date" name="next_dose_date" value="{{ old('next_dose_date') }}">
                                    <small class="form-text text-muted">Leave empty if this is the final dose</small>
                                    @error('next_dose_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes/Comments</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Enter any additional notes about the vaccination...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="side_effects" name="side_effects" value="1" {{ old('side_effects') ? 'checked' : '' }}>
                                <label class="form-check-label" for="side_effects">
                                    Patient experienced side effects
                                </label>
                            </div>
                        </div>

                        <div id="side_effects_details" style="display: {{ old('side_effects') ? 'block' : 'none' }};">
                            <div class="mb-3">
                                <label for="side_effects_notes" class="form-label">Side Effects Details</label>
                                <textarea class="form-control @error('side_effects_notes') is-invalid @enderror" 
                                          id="side_effects_notes" name="side_effects_notes" rows="3" 
                                          placeholder="Describe any side effects experienced...">{{ old('side_effects_notes') }}</textarea>
                                @error('side_effects_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-syringe"></i> Record Vaccination
                                </button>
                                <a href="{{ route('hospital.appointments.show', $appointment->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

@push('scripts')
<script>
document.getElementById('side_effects').addEventListener('change', function() {
    const detailsDiv = document.getElementById('side_effects_details');
    if (this.checked) {
        detailsDiv.style.display = 'block';
    } else {
        detailsDiv.style.display = 'none';
        document.getElementById('side_effects_notes').value = '';
    }
});
</script>
@endpush
@endsection
