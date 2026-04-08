<x-landing-layout title="Products">
    <div class="bg-white py-5">
        <div class="container">
            <div class="mb-4">
                <h1 class="h2 fw-semibold text-dark">{{ __('Products') }}</h1>
                <p class="text-secondary small mt-1 mb-0">{{ __('Browse smart security products and add them to your cart.') }}</p>
            </div>

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="h5 mb-0">{{ __('AI Security Assistant') }}</h2>
                        <div class="small text-secondary">{{ __('Recommendations, safety score, fraud insights, and chat guidance.') }}</div>
                    </div>

                    <form method="GET" action="{{ route('landing.products', absolute: false) }}" class="row g-3">
                        <input type="hidden" name="company" value="{{ $filters['company'] ?? '' }}">
                        <input type="hidden" name="category" value="{{ $filters['category'] ?? '' }}">
                        <input type="hidden" name="max_quantity" value="{{ $filters['max_quantity'] ?? '' }}">

                        <div class="col-md-4">
                            <label class="form-label">{{ __('Property type') }}</label>
                            <select name="property_type" class="form-select">
                                <option value="">{{ __('Select') }}</option>
                                <option value="apartment" @selected(($aiFilters['property_type'] ?? '') === 'apartment')>{{ __('Apartment') }}</option>
                                <option value="condo" @selected(($aiFilters['property_type'] ?? '') === 'condo')>{{ __('Condo') }}</option>
                                <option value="house" @selected(($aiFilters['property_type'] ?? '') === 'house')>{{ __('House') }}</option>
                                <option value="villa" @selected(($aiFilters['property_type'] ?? '') === 'villa')>{{ __('Villa') }}</option>
                                <option value="townhouse" @selected(($aiFilters['property_type'] ?? '') === 'townhouse')>{{ __('Townhouse') }}</option>
                                <option value="commercial" @selected(($aiFilters['property_type'] ?? '') === 'commercial')>{{ __('Commercial') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('Home size (sq ft)') }}</label>
                            <input type="number" name="property_size" min="100" max="10000" class="form-control" value="{{ $aiFilters['property_size'] ?? '' }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('Budget ($)') }}</label>
                            <input type="number" name="budget" min="0" step="0.01" class="form-control" value="{{ $aiFilters['budget'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('Entry points') }}</label>
                            <input type="number" name="entry_points" min="1" max="20" class="form-control" value="{{ $aiFilters['entry_points'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('Exit points') }}</label>
                            <input type="number" name="exit_points" min="1" max="20" class="form-control" value="{{ $aiFilters['exit_points'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('Neighborhood risk') }}</label>
                            <select name="neighborhood_profile" class="form-select">
                                <option value="">{{ __('Select') }}</option>
                                <option value="very_safe" @selected(($aiFilters['neighborhood_profile'] ?? '') === 'very_safe')>{{ __('Very Safe') }}</option>
                                <option value="safe" @selected(($aiFilters['neighborhood_profile'] ?? '') === 'safe')>{{ __('Safe') }}</option>
                                <option value="moderate" @selected(($aiFilters['neighborhood_profile'] ?? '') === 'moderate')>{{ __('Moderate') }}</option>
                                <option value="risky" @selected(($aiFilters['neighborhood_profile'] ?? '') === 'risky')>{{ __('Risky') }}</option>
                                <option value="high_crime" @selected(($aiFilters['neighborhood_profile'] ?? '') === 'high_crime')>{{ __('High Crime') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('Occupancy') }}</label>
                            <select name="occupancy_pattern" class="form-select">
                                <option value="">{{ __('Select') }}</option>
                                <option value="always_occupied" @selected(($aiFilters['occupancy_pattern'] ?? '') === 'always_occupied')>{{ __('Always occupied') }}</option>
                                <option value="mostly_occupied" @selected(($aiFilters['occupancy_pattern'] ?? '') === 'mostly_occupied')>{{ __('Mostly occupied') }}</option>
                                <option value="partially_occupied" @selected(($aiFilters['occupancy_pattern'] ?? '') === 'partially_occupied')>{{ __('Partially occupied') }}</option>
                                <option value="rarely_occupied" @selected(($aiFilters['occupancy_pattern'] ?? '') === 'rarely_occupied')>{{ __('Rarely occupied') }}</option>
                                <option value="vacant" @selected(($aiFilters['occupancy_pattern'] ?? '') === 'vacant')>{{ __('Vacant') }}</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_security_system" value="1" id="has_security_system" @checked(($aiFilters['has_security_system'] ?? false))>
                                <label class="form-check-label" for="has_security_system">{{ __('Existing security system') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="previous_incidents" value="1" id="previous_incidents" @checked(($aiFilters['previous_incidents'] ?? false))>
                                <label class="form-check-label" for="previous_incidents">{{ __('Previous incidents') }}</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
                            <button name="ai_recommend" value="1" class="btn btn-dark">{{ __('Get AI Recommendations') }}</button>
                            <button name="ai_score" value="1" class="btn btn-outline-primary">{{ __('Analyze Home Safety Score') }}</button>
                            <a href="{{ route('landing.products', absolute: false) }}" class="btn btn-link text-decoration-none">{{ __('Reset AI Inputs') }}</a>
                        </div>
                    </form>

                    <div class="row g-3 mt-1">
                        <div class="col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">{{ __('AI Product Recommendations') }}</div>
                                <div class="fw-semibold mb-1">
                                    {{ $usingAI ? __('Active') : __('Inactive') }}
                                </div>
                                <div class="small text-secondary">{{ __('Uses collaborative + content-based filtering from your context and history.') }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">{{ __('Home Safety Score') }}</div>
                                @if ($quickSafetyScore)
                                    <div class="fw-semibold mb-1">{{ $quickSafetyScore['score'] }}/100 ({{ ucfirst($quickSafetyScore['risk_level']) }} risk)</div>
                                @else
                                    <div class="fw-semibold mb-1">{{ __('Not analyzed yet') }}</div>
                                @endif
                                <div class="small text-secondary">{{ __('Classification and scoring based on home profile and risk indicators.') }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">{{ __('Fraud Detection Monitor') }}</div>
                                <div class="fw-semibold mb-1">
                                    {{ $fraudInsights['enabled'] ? __('Enabled') : __('Disabled') }}
                                </div>
                                <div class="small text-secondary">
                                    @auth
                                        {{ __('Open alerts') }}: {{ $fraudInsights['open_alerts'] }} | {{ __('High risk') }}: {{ $fraudInsights['high_risk_alerts'] }}
                                    @else
                                        {{ __('Login to view your personal fraud risk signals.') }}
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">{{ __('AI Chatbot for Security Guidance') }}</h2>
                    <div id="ai-chatbot-messages" class="border rounded-3 p-3 mb-3 bg-light" style="max-height: 220px; overflow-y: auto;">
                        <div class="small text-secondary">{{ __('Ask about product selection, setup help, or best security practices.') }}</div>
                    </div>
                    <form id="ai-chatbot-form" class="d-flex gap-2">
                        @csrf
                        <input type="text" id="ai-chatbot-input" class="form-control" maxlength="1000" placeholder="{{ __('Type your security question...') }}" required>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </form>
                </div>
            </div>

            <form method="GET" action="{{ route('landing.products', absolute: false) }}" class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="category" class="form-label">{{ __('Category') }}</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">{{ __('All categories') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="company" class="form-label">{{ __('Company') }}</label>
                            <select id="company" name="company" class="form-select">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand }}" @selected(request('company') === $brand)>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="max_quantity" class="form-label">{{ __('Max quantity') }}</label>
                            <input
                                id="max_quantity"
                                name="max_quantity"
                                type="number"
                                min="0"
                                value="{{ request('max_quantity') }}"
                                placeholder="{{ __('Any') }}"
                                class="form-control"
                            />
                        </div>

                        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                            <a href="{{ route('landing.products', absolute: false) }}" class="btn btn-link text-decoration-none">{{ __('Clear') }}</a>
                        </div>
                    </div>
                </div>
            </form>

            @if ($products->count() === 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <p class="text-secondary mb-0">{{ __('No products found.') }}</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <article class="card h-100 shadow-sm">
                                <a href="{{ route('landing.product.show', $product) }}" class="text-decoration-none text-dark">
                                    <div class="ratio ratio-4x3 bg-light">
                                        @if ($product->cover_image_url)
                                            <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="object-fit-cover rounded-top" />
                                        @else
                                            <div class="d-flex align-items-center justify-content-center text-secondary small">{{ __('No image') }}</div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2 small text-secondary mb-2">
                                            <span class="text-truncate">{{ $product->brand ?: __('—') }}</span>
                                            <span class="flex-shrink-0 fw-semibold text-primary">${{ number_format($product->price, 2) }}</span>
                                        </div>
                                        <h3 class="h6 fw-semibold line-clamp-2">{{ $product->name }}</h3>
                                        <p class="small text-secondary line-clamp-2 mb-0">
                                            {{ $product->summary ?? \Illuminate\Support\Str::limit($product->description, 90) }}
                                        </p>
                                    </div>
                                </a>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary w-100">{{ __('Add to cart') }}</button>
                                    </form>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('ai-chatbot-form');
            const input = document.getElementById('ai-chatbot-input');
            const messages = document.getElementById('ai-chatbot-messages');

            if (!form || !input || !messages) return;

            const appendMessage = (label, text) => {
                const row = document.createElement('div');
                row.className = 'small mb-2';
                row.innerHTML = `<strong>${label}:</strong> ${text}`;
                messages.appendChild(row);
                messages.scrollTop = messages.scrollHeight;
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const message = input.value.trim();
                if (!message) return;

                appendMessage('You', message);
                input.value = '';

                try {
                    const response = await fetch('{{ route('chatbot.chat', absolute: false) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await response.json();
                    appendMessage('AI', data.message || 'Unable to generate a response right now.');
                } catch (error) {
                    appendMessage('AI', 'Chat service temporarily unavailable.');
                }
            });
        })();
    </script>
</x-landing-layout>
