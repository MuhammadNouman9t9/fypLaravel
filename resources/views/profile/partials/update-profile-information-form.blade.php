<section>
    <header class="mb-3">
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Profile Information') }}
        </h2>

        <p class="small text-secondary">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">{{ __('First name') }}</label>
                <input
                    id="first_name"
                    name="first_name"
                    type="text"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name', $user->first_name) }}"
                    required
                    autofocus
                    autocomplete="given-name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">{{ __('Last name') }}</label>
                <input
                    id="last_name"
                    name="last_name"
                    type="text"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name', $user->last_name) }}"
                    required
                    autocomplete="family-name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input
                id="email"
                name="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('Phone number') }}</label>
            <input
                id="phone"
                name="phone"
                type="tel"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user->phone) }}"
                required
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            @if (is_null($user->phone_verified_at))
                <p class="small text-secondary mt-2 mb-0">
                    {{ __('We\'ll prompt you to verify this number before scheduling deliveries.') }}
                </p>
            @endif
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input
                    id="marketing_opt_in"
                    name="marketing_opt_in"
                    type="checkbox"
                    value="1"
                    class="form-check-input"
                    @checked(old('marketing_opt_in', $user->marketing_opt_in))
                >
                <label for="marketing_opt_in" class="form-check-label">{{ __('Receive security insights & device tips') }}</label>
            </div>
            <p class="small text-secondary ms-4 mb-0">
                {{ __('Opt in to receive periodic recommendations tailored to your SafeNest setup.') }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="small text-success mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
