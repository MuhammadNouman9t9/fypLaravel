@extends('layouts.storefront')

@section('content')
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-4xl px-4 py-12">
            <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">{{ __('SafeNest Concierge') }}</p>
            <h1 class="mt-3 text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                {{ __('Connect with a SafeNest security expert') }}
            </h1>
            <p class="mt-4 text-base leading-relaxed text-[#475569]">
                {{ __('Tell us a bit about your property and we will pair you with a specialist who can configure smart devices, review your AI risk score, and build a monitoring plan tailored to your home.') }}
            </p>
        </div>
    </section>

    <section class="mx-auto grid max-w-4xl gap-8 px-4 py-12 lg:grid-cols-[1fr,0.6fr]">
        <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
            <form class="space-y-5">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-[#1f2937]">{{ __('Full name') }}</label>
                    <input id="full_name" type="text" class="mt-2 w-full rounded-xl border border-[#d1d5db] px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" placeholder="{{ __('e.g. Alex Johnson') }}">
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#1f2937]">{{ __('Email address') }}</label>
                        <input id="email" type="email" class="mt-2 w-full rounded-xl border border-[#d1d5db] px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" placeholder="you@safenest.com">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-[#1f2937]">{{ __('Phone number') }}</label>
                        <input id="phone" type="tel" class="mt-2 w-full rounded-xl border border-[#d1d5db] px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" placeholder="+1 555 123 4567">
                    </div>
                </div>

                <div>
                    <label for="property_type" class="block text-sm font-medium text-[#1f2937]">{{ __('Property type') }}</label>
                    <select id="property_type" class="mt-2 w-full rounded-xl border border-[#d1d5db] bg-white px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10">
                        <option value="">{{ __('Select property type') }}</option>
                        <option>{{ __('Apartment / Condo') }}</option>
                        <option>{{ __('Single-family Home') }}</option>
                        <option>{{ __('Townhouse') }}</option>
                        <option>{{ __('Multi-unit Building') }}</option>
                        <option>{{ __('Commercial Space') }}</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-[#1f2937]">{{ __('How can we help?') }}</label>
                    <textarea id="message" rows="4" class="mt-2 w-full rounded-xl border border-[#d1d5db] px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" placeholder="{{ __('Tell us about your current setup, goals, or questions...') }}"></textarea>
                </div>

                <button type="button" class="w-full rounded-full bg-[#111827] px-4 py-3 text-sm font-semibold text-white hover:bg-[#0b1120]">
                    {{ __('Request consultation') }}
                </button>

                <p class="text-xs text-[#94a3b8]">
                    {{ __('We respond within one business day. By submitting you agree to be contacted by a SafeNest specialist and receive follow-up resources via email.') }}
                </p>
            </form>
        </div>

        <aside id="schedule" class="space-y-4 rounded-3xl border border-[#cbd5f5] bg-[#eef2ff] p-6 text-sm text-[#312e81] shadow-sm">
            <h2 class="text-base font-semibold text-[#312e81]">{{ __('What to expect') }}</h2>
            <ul class="space-y-3 text-[#4338ca]">
                <li>• {{ __('Personalized device layout aligned with SafeNest AI analytics') }}</li>
                <li>• {{ __('Fraud prevention best practices and monitoring schedules') }}</li>
                <li>• {{ __('Recommendations on installation partners in your area') }}</li>
            </ul>
            <div class="rounded-2xl border border-white/30 bg-white/50 p-4 text-[#312e81]">
                <p class="text-xs uppercase tracking-wide text-[#4338ca]">{{ __('Need immediate assistance?') }}</p>
                <p class="mt-2">{{ __('Email concierge@safenest.ai or call +1 (800) 555-0119 for priority support.') }}</p>
            </div>
        </aside>
    </section>
@endsection

