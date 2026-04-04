<section>
    <header>
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Addresses') }}
        </h2>

        <p class="mt-1 small text-secondary">
            {{ __('Store shipping and billing addresses to speed up checkout and coordinate expert visits.') }}
        </p>
    </header>

    @php($addressFormState = session('address_form'))

    @if (in_array(session('status'), ['address-created', 'address-updated', 'address-deleted']))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="mt-4 alert alert-success small py-2"
        >
            @switch(session('status'))
                @case('address-created')
                    {{ __('Address saved successfully.') }}
                    @break
                @case('address-updated')
                    {{ __('Address updated successfully.') }}
                    @break
                @case('address-deleted')
                    {{ __('Address removed.') }}
                    @break
            @endswitch
        </div>
    @endif

    <form method="post" action="{{ route('profile.addresses.store') }}" class="mt-4">
        @csrf

        <h3 class="small fw-semibold text-dark mb-3">
            {{ __('Add a new address') }}
        </h3>

        <div>
            <label for="address_full_name" class="form-label">{{ __('Full Name') }}</label>
            <input 
                id="address_full_name" 
                name="full_name" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('full_name', $user->first_name . ' ' . $user->last_name) }}" 
                required 
                placeholder="{{ __('Enter your full name') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        <div>
            <label for="address_phone" class="form-label">{{ __('Phone Number') }}</label>
            <input 
                id="address_phone" 
                name="phone" 
                type="tel" 
                class="form-control mt-2" 
                value="{{ old('phone', $user->phone) }}" 
                required 
                placeholder="{{ __('Enter your phone number') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <label for="address_email" class="form-label">{{ __('Email') }} <span class="text-secondary small">({{ __('optional') }})</span></label>
            <input 
                id="address_email" 
                name="email" 
                type="email" 
                class="form-control mt-2" 
                value="{{ old('email', $user->email) }}" 
                placeholder="{{ __('Enter your email (optional)') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="address_line_one" class="form-label">{{ __('House/Flat/Building No') }}</label>
            <input 
                id="address_line_one" 
                name="line_one" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('line_one') }}" 
                required 
                placeholder="{{ __('Enter house/flat/building number') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('line_one')" />
        </div>

        <div>
            <label for="address_line_two" class="form-label">{{ __('Street / Area') }}</label>
            <input 
                id="address_line_two" 
                name="line_two" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('line_two') }}" 
                placeholder="{{ __('Enter street name or area') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('line_two')" />
        </div>

        <div>
            <label for="address_city" class="form-label">{{ __('City') }}</label>
            <input 
                id="address_city" 
                name="city" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('city') }}" 
                required 
                placeholder="{{ __('Enter city name') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <label for="address_postal_code" class="form-label">{{ __('Postal Code') }}</label>
            <input 
                id="address_postal_code" 
                name="postal_code" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('postal_code') }}" 
                placeholder="{{ __('Enter postal code') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <label for="address_state" class="form-label">{{ __('Province') }}</label>
            <input 
                id="address_state" 
                name="state" 
                type="text" 
                class="form-control mt-2" 
                value="{{ old('state') }}" 
                placeholder="{{ __('Enter province/state') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div>
            <label for="address_additional_notes" class="form-label">{{ __('Additional Notes') }} <span class="text-secondary small">({{ __('optional') }})</span></label>
            <textarea 
                id="address_additional_notes" 
                name="additional_notes" 
                rows="3"
                class="form-control mt-2" 
                placeholder="{{ __('Any additional notes or instructions...') }}"
            >{{ old('additional_notes') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('additional_notes')" />
        </div>

        <input type="hidden" name="country_code" value="US">

        <div class="d-flex align-items-center gap-2 flex-wrap pt-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                {{ __('Save Address') }}
            </button>
            <button type="button" onclick="window.location.href='{{ route('cart.checkout') }}'" class="btn btn-outline-primary rounded-pill px-4">
                {{ __('Proceed') }}
            </button>
        </div>
    </form>

    <div class="mt-10">
        <h3 class="small fw-semibold text-dark mt-4">
            {{ __('Saved addresses') }}
        </h3>

        @if ($user->addresses->isEmpty())
            <p class="mt-3 small text-secondary">
                {{ __('You have not saved any addresses yet.') }}
            </p>
        @else
            <div class="mt-4 d-flex flex-column gap-4">
                @foreach ($user->addresses as $address)
                    @php($isEditing = $addressFormState === 'edit-'.$address->id)
                    <div class="rounded border bg-white px-3 py-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between">
                            <div>
                                <p class="small fw-semibold text-dark">
                                    {{ $address->label ?? ucfirst($address->type) }}
                                </p>
                                <p class="small text-secondary mb-1">
                                    {{ $address->first_name }} {{ $address->last_name }}
                                </p>
                                <p class="small text-secondary mb-1">
                                    {{ $address->line_one }}@if($address->line_two), {{ $address->line_two }}@endif, {{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) {{ $address->postal_code }}@endif
                                </p>
                                <p class="small text-secondary mb-1">
                                    {{ strtoupper($address->country_code) }}
                                </p>
                                @if ($address->phone)
                                    <p class="small text-secondary mb-1">{{ $address->phone }}</p>
                                @endif
                                @if ($address->email)
                                    <p class="small text-secondary mb-1">{{ $address->email }}</p>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge rounded-pill text-bg-light border">
                                    {{ __($address->type) }}
                                </span>
                                @if ($address->is_primary)
                                    <span class="badge text-bg-primary">
                                        {{ __('Primary') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <details class="mt-4" @if($addressFormState === 'edit-'.$address->id) open @endif>
                            <summary class="small fw-semibold text-primary" style="cursor: pointer;">
                                {{ __('Update this address') }}
                            </summary>
                            <form method="post" action="{{ route('profile.addresses.update', $address) }}" class="mt-3">
                                @csrf
                                @method('patch')

                                <div>
                                    <label :for="'address_'.$address->id.'_full_name'" class="form-label">{{ __('Full Name') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_full_name'" 
                                        name="full_name" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('full_name', $address->first_name . ' ' . $address->last_name) : ($address->first_name . ' ' . $address->last_name)" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_phone'" class="form-label">{{ __('Phone Number') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_phone'" 
                                        name="phone" 
                                        type="tel" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('phone', $address->phone) : $address->phone" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_email'" class="form-label">{{ __('Email') }} <span class="text-secondary small">({{ __('optional') }})</span></label>
                                    <input 
                                        :id="'address_'.$address->id.'_email'" 
                                        name="email" 
                                        type="email" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('email', $address->email) : $address->email" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_line_one'" class="form-label">{{ __('House/Flat/Building No') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_line_one'" 
                                        name="line_one" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('line_one', $address->line_one) : $address->line_one" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('line_one')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_line_two'" class="form-label">{{ __('Street / Area') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_line_two'" 
                                        name="line_two" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('line_two', $address->line_two) : $address->line_two" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('line_two')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_city'" class="form-label">{{ __('City') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_city'" 
                                        name="city" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('city', $address->city) : $address->city" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_postal_code'" class="form-label">{{ __('Postal Code') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_postal_code'" 
                                        name="postal_code" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('postal_code', $address->postal_code) : $address->postal_code" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_state'" class="form-label">{{ __('Province') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_state'" 
                                        name="state" 
                                        type="text" 
                                        class="form-control mt-2" 
                                        :value="$isEditing ? old('state', $address->state) : $address->state" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_additional_notes'" class="form-label">{{ __('Additional Notes') }} <span class="text-secondary small">({{ __('optional') }})</span></label>
                                    <textarea 
                                        :id="'address_'.$address->id.'_additional_notes'" 
                                        name="additional_notes" 
                                        rows="3"
                                        class="form-control mt-2"
                                    >{{ $isEditing ? old('additional_notes', $address->meta['additional_notes'] ?? '') : ($address->meta['additional_notes'] ?? '') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('additional_notes')" />
                                </div>

                                <input type="hidden" name="country_code" :value="$address->country_code">

                                <div class="d-flex align-items-center gap-2 flex-wrap pt-3">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                                        {{ __('Save Address') }}
                                    </button>
                                    <button type="button" onclick="window.location.href='{{ route('cart.index') }}'" class="btn btn-outline-dark rounded-pill px-4">
                                        {{ __('Proceed') }}
                                    </button>
                                </div>
                            </form>
                        </details>

                        <form method="post" action="{{ route('profile.addresses.destroy', $address) }}" class="mt-3 d-flex justify-content-end">
                            @csrf
                            @method('delete')
                            <button
                                type="submit"
                                class="btn btn-link btn-sm text-danger text-decoration-none p-0"
                                onclick="return confirm('{{ __('Remove this address?') }}');"
                            >
                                {{ __('Delete address') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>


