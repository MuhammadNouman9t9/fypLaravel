<x-landing-layout title="Support">
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Support</h1>
                <p class="text-gray-600">Connect with our security experts or view your conversations</p>
            </div>

            @if (session('status') === 'consultation-requested')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-green-800 font-medium">Your consultation request has been submitted successfully! Our security expert will contact you within one business day.</p>
                    </div>
                </div>
            @elseif (session('status'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Consultation Request Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm mb-8">
                <div class="text-center mb-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-600 mb-2">SafeNest Concierge</p>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Connect with a SafeNest Security Expert
                    </h2>
                    <p class="text-gray-600">
                        Tell us a bit about your property and we will pair you with a specialist who can configure smart devices, review your AI risk score, and build a monitoring plan tailored to your home.
                    </p>
                </div>

                @auth
                <form method="POST" action="{{ route('support.experts.store') }}" class="space-y-5 max-w-2xl mx-auto">
                    @csrf
                    
                    <!-- Property Type -->
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-900 mb-2">Property Type</label>
                        <div class="relative">
                            <select 
                                id="property_type" 
                                name="property_type" 
                                class="appearance-none w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 cursor-pointer"
                                required
                            >
                                <option value="">Select property type</option>
                                <option value="house" {{ old('property_type') === 'house' ? 'selected' : '' }}>House</option>
                                <option value="apartment" {{ old('property_type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="condo" {{ old('property_type') === 'condo' ? 'selected' : '' }}>Condo</option>
                                <option value="business" {{ old('property_type') === 'business' ? 'selected' : '' }}>Business</option>
                                <option value="other" {{ old('property_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('property_type')" class="mt-2" />
                    </div>

                    <!-- Property Size -->
                    <div>
                        <label for="property_size" class="block text-sm font-medium text-gray-900 mb-2">Property Size</label>
                        <div class="relative">
                            <select 
                                id="property_size" 
                                name="property_size" 
                                class="appearance-none w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 cursor-pointer"
                                required
                            >
                                <option value="">Select property size</option>
                                <option value="small" {{ old('property_size') === 'small' ? 'selected' : '' }}>Small (under 1,500 sq ft)</option>
                                <option value="medium" {{ old('property_size') === 'medium' ? 'selected' : '' }}>Medium (1,500 - 3,000 sq ft)</option>
                                <option value="large" {{ old('property_size') === 'large' ? 'selected' : '' }}>Large (3,000 - 5,000 sq ft)</option>
                                <option value="xlarge" {{ old('property_size') === 'xlarge' ? 'selected' : '' }}>Extra Large (5,000+ sq ft)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('property_size')" class="mt-2" />
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-900 mb-2">How can we help?</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="4" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                            placeholder="Tell us about your current setup, goals, or questions..."
                            required
                        >{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Request Consultation
                    </button>

                    <p class="text-xs text-gray-500 text-center">
                        We respond within one business day. By submitting you agree to be contacted by a SafeNest specialist and receive follow-up resources via email.
                    </p>
                </form>
                @else
                <div class="text-center py-8 max-w-2xl mx-auto">
                    <p class="text-gray-600 mb-4">Please login to request a consultation with our security experts.</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Login to Continue
                    </a>
                </div>
                @endauth
            </div>

            <!-- My Conversations Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">My Conversations</h2>
            </div>

            @if ($conversations->isEmpty())
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No conversations yet</h3>
                    <p class="text-gray-600">Submit a consultation request above to get started.</p>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($conversations as $conversation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $conversation->subject ?? 'No Subject' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $conversation->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $conversation->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $conversation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            ">
                                                {{ ucfirst($conversation->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('support.show', $conversation) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $conversations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-landing-layout>
