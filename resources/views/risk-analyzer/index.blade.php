<x-landing-layout title="Risk Analyzer">
    <div class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="text-center mb-5">
                        <span class="badge text-bg-primary-subtle text-primary mb-3">{{ __('AI-Powered') }}</span>
                        <h1 class="h2 fw-bold">{{ __('Property Security Risk Analyzer') }}</h1>
                        <p class="text-secondary mb-0">{{ __('Answer a few questions about your property and get a personalized security risk assessment with product recommendations.') }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('risk-analyzer.analyze') }}" method="POST">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Property Type') }} *</label>
                                        <select name="property_type" required class="form-select">
                                            <option value="">-- {{ __('Select') }} --</option>
                                            <option value="apartment" {{ old('property_type') === 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                            <option value="condo" {{ old('property_type') === 'condo' ? 'selected' : '' }}>{{ __('Condo') }}</option>
                                            <option value="house" {{ old('property_type') === 'house' ? 'selected' : '' }}>{{ __('House') }}</option>
                                            <option value="villa" {{ old('property_type') === 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                            <option value="townhouse" {{ old('property_type') === 'townhouse' ? 'selected' : '' }}>{{ __('Townhouse') }}</option>
                                            <option value="commercial" {{ old('property_type') === 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Property Size (sq ft)') }} *</label>
                                        <input type="number" name="property_size" min="100" max="10000" value="{{ old('property_size', 1500) }}" required class="form-control">
                                        <div class="form-text">{{ __('Between 100 and 10,000 sq ft.') }}</div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Occupancy Pattern') }} *</label>
                                        <select name="occupancy_pattern" required class="form-select">
                                            <option value="">-- {{ __('Select') }} --</option>
                                            <option value="always_occupied" {{ old('occupancy_pattern') === 'always_occupied' ? 'selected' : '' }}>{{ __('Always occupied') }}</option>
                                            <option value="mostly_occupied" {{ old('occupancy_pattern') === 'mostly_occupied' ? 'selected' : '' }}>{{ __('Mostly occupied') }}</option>
                                            <option value="partially_occupied" {{ old('occupancy_pattern') === 'partially_occupied' ? 'selected' : '' }}>{{ __('Partially occupied') }}</option>
                                            <option value="rarely_occupied" {{ old('occupancy_pattern') === 'rarely_occupied' ? 'selected' : '' }}>{{ __('Rarely occupied') }}</option>
                                            <option value="vacant" {{ old('occupancy_pattern') === 'vacant' ? 'selected' : '' }}>{{ __('Vacant') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Neighborhood Profile') }} *</label>
                                        <select name="neighborhood_profile" required class="form-select">
                                            <option value="">-- {{ __('Select') }} --</option>
                                            <option value="very_safe" {{ old('neighborhood_profile') === 'very_safe' ? 'selected' : '' }}>{{ __('Very safe') }}</option>
                                            <option value="safe" {{ old('neighborhood_profile') === 'safe' ? 'selected' : '' }}>{{ __('Safe') }}</option>
                                            <option value="moderate" {{ old('neighborhood_profile') === 'moderate' ? 'selected' : '' }}>{{ __('Moderate') }}</option>
                                            <option value="risky" {{ old('neighborhood_profile') === 'risky' ? 'selected' : '' }}>{{ __('Risky') }}</option>
                                            <option value="high_crime" {{ old('neighborhood_profile') === 'high_crime' ? 'selected' : '' }}>{{ __('High crime') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Entry Points (doors)') }} *</label>
                                        <input type="number" name="entry_points" min="1" max="20" value="{{ old('entry_points', 2) }}" required class="form-control">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Exit Points (doors/windows)') }} *</label>
                                        <input type="number" name="exit_points" min="1" max="20" value="{{ old('exit_points', 2) }}" required class="form-control">
                                    </div>

                                    <div class="col-12">
                                        <div class="border rounded-3 p-3 bg-body-secondary bg-opacity-10">
                                            <div class="d-flex flex-wrap gap-4">
                                                <div class="form-check">
                                                    <input type="hidden" name="has_security_system" value="0">
                                                    <input type="checkbox" name="has_security_system" value="1" {{ old('has_security_system') ? 'checked' : '' }} class="form-check-input" id="has-security">
                                                    <label class="form-check-label" for="has-security">{{ __('I already have a security system') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="previous_incidents" value="0">
                                                    <input type="checkbox" name="previous_incidents" value="1" {{ old('previous_incidents') ? 'checked' : '' }} class="form-check-input" id="prev-incidents">
                                                    <label class="form-check-label" for="prev-incidents">{{ __('Previous security incidents at this property') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-grid d-md-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Analyze My Risk') }}</button>
                                    <a href="{{ route('landing.home') }}" class="btn btn-outline-secondary btn-lg">{{ __('Cancel') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <p class="text-center text-secondary small mt-3 mb-0">
                        {{ __('Your input is used only to generate this assessment. We never share property details.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>
