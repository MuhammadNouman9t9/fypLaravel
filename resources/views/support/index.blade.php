<x-landing-layout title="Support">
    <div class="bg-light min-vh-100 py-4 py-lg-5">
        <div class="container" style="max-width: 60rem;">
            <div class="mb-4">
                <h1 class="h3 fw-bold text-dark mb-1">Support</h1>
                <p class="text-secondary mb-0">Connect with our security experts or view your conversations.</p>
            </div>

            @if (session('status') === 'consultation-requested')
                <div class="alert alert-success" role="alert">
                    Your consultation request has been submitted successfully! Our security expert will contact you within one business day.
                </div>
            @elseif (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <p class="small fw-semibold text-uppercase text-primary mb-2">SafeNest Concierge</p>
                        <h2 class="h4 fw-bold text-dark mb-2">Connect with a SafeNest Security Expert</h2>
                        <p class="text-secondary mb-0">
                            Tell us a bit about your property and we will pair you with a specialist who can configure smart devices, review your AI risk score, and build a monitoring plan tailored to your home.
                        </p>
                    </div>

                    @auth
                        <form method="POST" action="{{ route('support.experts.store') }}" class="mx-auto" style="max-width: 34rem;">
                            @csrf

                            <div class="mb-3">
                                <x-input-label for="property_type" :value="__('Property Type')" />
                                <select id="property_type" name="property_type" class="form-select mt-2" required>
                                    <option value="">Select property type</option>
                                    <option value="house" {{ old('property_type') === 'house' ? 'selected' : '' }}>House</option>
                                    <option value="apartment" {{ old('property_type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="condo" {{ old('property_type') === 'condo' ? 'selected' : '' }}>Condo</option>
                                    <option value="business" {{ old('property_type') === 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="other" {{ old('property_type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('property_type')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <x-input-label for="property_size" :value="__('Property Size')" />
                                <select id="property_size" name="property_size" class="form-select mt-2" required>
                                    <option value="">Select property size</option>
                                    <option value="small" {{ old('property_size') === 'small' ? 'selected' : '' }}>Small (under 1,500 sq ft)</option>
                                    <option value="medium" {{ old('property_size') === 'medium' ? 'selected' : '' }}>Medium (1,500 - 3,000 sq ft)</option>
                                    <option value="large" {{ old('property_size') === 'large' ? 'selected' : '' }}>Large (3,000 - 5,000 sq ft)</option>
                                    <option value="xlarge" {{ old('property_size') === 'xlarge' ? 'selected' : '' }}>Extra Large (5,000+ sq ft)</option>
                                </select>
                                <x-input-error :messages="$errors->get('property_size')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <x-input-label for="message" :value="__('How can we help?')" />
                                <textarea id="message" name="message" rows="4" class="form-control mt-2" placeholder="Tell us about your current setup, goals, or questions..." required>{{ old('message') }}</textarea>
                                <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Request Consultation') }}
                                </button>
                            </div>

                            <p class="small text-secondary text-center mt-3 mb-0">
                                We respond within one business day. By submitting you agree to be contacted by a SafeNest specialist and receive follow-up resources via email.
                            </p>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <p class="text-secondary mb-3">Please login to request a consultation with our security experts.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                {{ __('Login to Continue') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <h2 class="h5 fw-bold text-dark mb-3">My Conversations</h2>

            @if ($conversations->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center text-secondary py-5">
                        No conversations yet. Submit a consultation request above to get started.
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Last Message</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conversations as $conversation)
                                    <tr>
                                        <td>{{ $conversation->subject ?? 'No Subject' }}</td>
                                        <td>
                                            <span class="badge
                                                {{ $conversation->status === 'open' ? 'text-bg-success' : '' }}
                                                {{ $conversation->status === 'closed' ? 'text-bg-secondary' : '' }}
                                                {{ $conversation->status === 'pending' ? 'text-bg-warning' : '' }}
                                            ">
                                                {{ ucfirst($conversation->status) }}
                                            </span>
                                        </td>
                                        <td class="text-secondary">
                                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'Never' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('support.show', $conversation) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-body border-top">
                        {{ $conversations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-landing-layout>
