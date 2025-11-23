<section>
    <header>
        <h2 class="text-lg font-semibold text-[#0f172a]">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-[#475569]">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-[#1f2937]">{{ __('First name') }}</label>
                <input 
                    id="first_name" 
                    name="first_name" 
                    type="text" 
                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                    value="{{ old('first_name', $user->first_name) }}" 
                    required 
                    autofocus 
                    autocomplete="given-name" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-[#1f2937]">{{ __('Last name') }}</label>
                <input 
                    id="last_name" 
                    name="last_name" 
                    type="text" 
                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                    value="{{ old('last_name', $user->last_name) }}" 
                    required 
                    autocomplete="family-name" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[#1f2937]">{{ __('Email') }}</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="mt-2 w-full rounded-xl border border-[#d1d5db] bg-white px-3 py-2 text-sm text-[#111827] focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-[#1f2937]">{{ __('Phone number') }}</label>
            <input 
                id="phone" 
                name="phone" 
                type="tel" 
                class="mt-2 w-full rounded-xl border border-[#d1d5db] bg-white px-3 py-2 text-sm text-[#111827] focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10" 
                value="{{ old('phone', $user->phone) }}" 
                required 
                autocomplete="tel" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            @if (is_null($user->phone_verified_at))
                <p class="mt-2 text-sm text-[#6b7280]">
                    {{ __('We\'ll prompt you to verify this number before scheduling deliveries.') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <input
                id="marketing_opt_in"
                name="marketing_opt_in"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-purple-200 text-purple-600 focus:ring-purple-600"
                @checked(old('marketing_opt_in', $user->marketing_opt_in))
            >
            <div>
                <label for="marketing_opt_in" class="block text-sm font-medium text-[#1f2937] cursor-pointer">{{ __('Receive security insights & device tips') }}</label>
                <p class="text-sm text-[#6b7280]">
                    {{ __('Opt in to receive periodic recommendations tailored to your SafeNest setup.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-[#16a34a]"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
