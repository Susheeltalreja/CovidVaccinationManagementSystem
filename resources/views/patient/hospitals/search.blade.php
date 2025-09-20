@extends('layouts.patient')

@section('title', 'Search Hospitals - COVID Vaccination System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-search me-2"></i>
                Search Hospitals
            </h1>
            <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('patient.hospitals.search') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Search Hospitals</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by hospital name, location, or address...">
                        </div>
                        <div class="col-md-4">
                            <label for="type" class="form-label">Service Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Services</option>
                                <option value="covid_test" {{ request('type') == 'covid_test' ? 'selected' : '' }}>COVID Test</option>
                                <option value="vaccination" {{ request('type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Search
                        </button>
                        <a href="{{ route('patient.hospitals.search') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hospitals List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>
                    Available Hospitals
                    @if(request('search') || request('type'))
                        <span class="badge bg-primary ms-2">{{ $hospitals->total() }} results</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($hospitals->count() > 0)
                    <div class="row g-4">
                        @foreach($hospitals as $hospital)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-building display-6 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="card-title mb-1">{{ $hospital->name }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $hospital->location }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <p class="card-text text-muted small">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ Str::limit($hospital->address, 100) }}
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-success">Available</span>
                                            </div>
                                            <a href="{{ route('patient.hospitals.show', $hospital->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $hospitals->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-building-x display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">No hospitals found</h5>
                        <p class="text-muted">
                            @if(request('search') || request('type'))
                                Try adjusting your search criteria or 
                                <a href="{{ route('patient.hospitals.search') }}" class="text-decoration-none">view all hospitals</a>
                            @else
                                No hospitals are currently available in your area.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
