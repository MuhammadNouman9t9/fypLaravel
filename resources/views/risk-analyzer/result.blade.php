<x-landing-layout title="Risk Assessment Result">
    @php
        $riskColors = [
            'low' => 'success',
            'moderate' => 'info',
            'high' => 'warning',
            'critical' => 'danger',
        ];
        $color = $riskColors[$assessment->risk_level] ?? 'secondary';
    @endphp

    <div class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="text-center mb-4">
                        <h1 class="h2 fw-bold">{{ __('Your Security Risk Assessment') }}</h1>
                        <p class="text-secondary mb-0">{{ __('Generated on') }} {{ $assessment->analyzed_at?->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="bg-{{ $color }} bg-opacity-10 p-4 p-md-5 text-center border-bottom">
                            <div class="display-3 fw-bold text-{{ $color }}">{{ (int) $assessment->score }}<span class="fs-4 text-secondary">/100</span></div>
                            <div class="h5 text-uppercase fw-semibold text-{{ $color }} mb-0">
                                {{ __('Risk level') }}: {{ ucfirst($assessment->risk_level) }}
                            </div>
                            <p class="text-secondary mt-2 mb-0">
                                {{ __('Higher score = more secure. Lower score = higher risk.') }}
                            </p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted">{{ __('Property type') }}</div>
                                    <div class="fw-semibold">{{ ucfirst($assessment->property_type ?? '—') }}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted">{{ __('Property size') }}</div>
                                    <div class="fw-semibold">{{ number_format($assessment->property_size) }} sq ft</div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted">{{ __('Occupancy') }}</div>
                                    <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $assessment->occupancy_pattern)) }}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted">{{ __('Neighborhood') }}</div>
                                    <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $assessment->neighborhood_profile)) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $analysis = $assessment->analysis ?? [];
                        $riskFactors = $analysis['risk_factors'] ?? [];
                        $strengths = $analysis['strengths'] ?? [];
                    @endphp

                    @if (! empty($riskFactors) || ! empty($strengths))
                        <div class="row g-4 mb-4">
                            @if (! empty($riskFactors))
                                <div class="col-12 col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <h2 class="h6 fw-bold text-danger mb-3">{{ __('Risk Factors') }}</h2>
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($riskFactors as $factor)
                                                    <li class="mb-2 d-flex gap-2">
                                                        <span class="text-danger">✕</span>
                                                        <span class="text-secondary">{{ $factor }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (! empty($strengths))
                                <div class="col-12 col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <h2 class="h6 fw-bold text-success mb-3">{{ __('Strengths') }}</h2>
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($strengths as $strength)
                                                    <li class="mb-2 d-flex gap-2">
                                                        <span class="text-success">✓</span>
                                                        <span class="text-secondary">{{ $strength }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (! empty($assessment->recommendations))
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3">{{ __('Recommendations') }}</h2>
                                <div class="vstack gap-3">
                                    @foreach ($assessment->recommendations as $rec)
                                        @php
                                            $priorityColor = match ($rec['priority'] ?? '') {
                                                'critical' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <div class="border rounded-3 p-3">
                                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                                <h3 class="h6 fw-bold mb-0">{{ $rec['title'] ?? '' }}</h3>
                                                <span class="badge text-bg-{{ $priorityColor }}-subtle text-{{ $priorityColor }} text-uppercase">
                                                    {{ $rec['priority'] ?? '' }}
                                                </span>
                                            </div>
                                            <p class="text-secondary small mb-0">{{ $rec['description'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($recommendations->isNotEmpty())
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3">{{ __('Recommended Products') }}</h2>
                                <div class="row g-3">
                                    @foreach ($recommendations as $product)
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <a href="{{ route('landing.product.show', $product) }}" class="text-decoration-none">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    @if ($product->cover_image_url)
                                                        <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="card-img-top" style="height: 160px; object-fit: cover;">
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="small text-secondary mb-1">{{ $product->brand ?: '—' }}</div>
                                                        <h3 class="h6 fw-semibold text-dark line-clamp-2 mb-2">{{ $product->name }}</h3>
                                                        <div class="fw-bold text-primary">${{ number_format($product->price, 2) }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('risk-analyzer.index') }}" class="btn btn-primary">{{ __('Run Another Assessment') }}</a>
                        <a href="{{ route('landing.products') }}" class="btn btn-outline-secondary">{{ __('Browse All Products') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>
