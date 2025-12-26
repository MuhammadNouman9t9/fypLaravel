<section>
    <header>
        <h2 class="text-lg font-semibold text-[#0f172a]">
            {{ __('Addresses') }}
        </h2>

        <p class="mt-1 text-sm text-[#475569]">
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
            class="mt-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
        >
            @switch(session('status'))
                @case('address-created')
                    {{ __('Address saved successfully. Your profile is now verified!') }}
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

    <form method="post" action="{{ route('profile.addresses.store') }}" class="mt-6 space-y-6">
        @csrf

        <h3 class="text-sm font-semibold text-[#1f2937] mb-4">
            {{ __('Add a new address') }}
        </h3>

        <div>
            <label for="address_full_name" class="block text-sm font-medium text-[#1f2937]">{{ __('Full Name') }}</label>
            <input 
                id="address_full_name" 
                name="full_name" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('full_name', $user->first_name . ' ' . $user->last_name) }}" 
                required 
                placeholder="{{ __('Enter your full name') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        <div>
            <label for="address_phone" class="block text-sm font-medium text-[#1f2937]">{{ __('Phone Number') }}</label>
            <input 
                id="address_phone" 
                name="phone" 
                type="tel" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('phone', $user->phone) }}" 
                required 
                placeholder="{{ __('Enter your phone number') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <label for="address_email" class="block text-sm font-medium text-[#1f2937]">{{ __('Email') }} <span class="text-[#6b7280] text-xs">({{ __('optional') }})</span></label>
            <input 
                id="address_email" 
                name="email" 
                type="email" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('email', $user->email) }}" 
                placeholder="{{ __('Enter your email (optional)') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="address_line_one" class="block text-sm font-medium text-[#1f2937]">{{ __('House/Flat/Building No') }}</label>
            <input 
                id="address_line_one" 
                name="line_one" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('line_one') }}" 
                required 
                placeholder="{{ __('Enter house/flat/building number') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('line_one')" />
        </div>

        <div>
            <label for="address_line_two" class="block text-sm font-medium text-[#1f2937]">{{ __('Street / Area') }}</label>
            <input 
                id="address_line_two" 
                name="line_two" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('line_two') }}" 
                placeholder="{{ __('Enter street name or area') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('line_two')" />
        </div>

        <div>
            <label for="address_city" class="block text-sm font-medium text-[#1f2937]">{{ __('City') }}</label>
            <input 
                id="address_city" 
                name="city" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('city') }}" 
                required 
                placeholder="{{ __('Enter city name') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <label for="address_postal_code" class="block text-sm font-medium text-[#1f2937]">{{ __('Postal Code') }}</label>
            <input 
                id="address_postal_code" 
                name="postal_code" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('postal_code') }}" 
                placeholder="{{ __('Enter postal code') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <label for="address_state" class="block text-sm font-medium text-[#1f2937]">{{ __('Province') }}</label>
            <input 
                id="address_state" 
                name="state" 
                type="text" 
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                value="{{ old('state') }}" 
                placeholder="{{ __('Enter province/state') }}"
            />
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div>
            <label for="address_additional_notes" class="block text-sm font-medium text-[#1f2937]">{{ __('Additional Notes') }} <span class="text-[#6b7280] text-xs">({{ __('optional') }})</span></label>
            <textarea 
                id="address_additional_notes" 
                name="additional_notes" 
                rows="3"
                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                placeholder="{{ __('Any additional notes or instructions...') }}"
            >{{ old('additional_notes') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('additional_notes')" />
        </div>

        <input type="hidden" name="country_code" value="US">

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition">
                {{ __('Save Address') }}
            </button>
            <button type="button" onclick="window.location.href='{{ route('cart.checkout') }}'" class="rounded-full border border-purple-600 bg-white px-6 py-2.5 text-sm font-semibold text-purple-600 hover:bg-purple-50 transition">
                {{ __('Proceed') }}
            </button>
        </div>
    </form>

    <div class="mt-10">
        <h3 class="text-sm font-semibold text-gray-800">
            {{ __('Saved addresses') }}
        </h3>

        @if ($user->addresses->isEmpty())
            <p class="mt-3 text-sm text-gray-600">
                {{ __('You have not saved any addresses yet.') }}
            </p>
        @else
            <div class="mt-4 space-y-5">
                @foreach ($user->addresses as $address)
                    @php($isEditing = $addressFormState === 'edit-'.$address->id)
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-5 shadow-sm">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $address->label ?? ucfirst($address->type) }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $address->first_name }} {{ $address->last_name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $address->line_one }}@if($address->line_two), {{ $address->line_two }}@endif, {{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) {{ $address->postal_code }}@endif
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ strtoupper($address->country_code) }}
                                </p>
                                @if ($address->phone)
                                    <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                @endif
                                @if ($address->email)
                                    <p class="text-sm text-gray-600">{{ $address->email }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                    {{ __($address->type) }}
                                </span>
                                @if ($address->is_primary)
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-600">
                                        {{ __('Primary') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <details class="mt-4" @if($addressFormState === 'edit-'.$address->id) open @endif>
                            <summary class="cursor-pointer text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                                {{ __('Update this address') }}
                            </summary>
                            <form method="post" action="{{ route('profile.addresses.update', $address) }}" class="mt-4 space-y-4">
                                @csrf
                                @method('patch')

                                <div>
                                    <label :for="'address_'.$address->id.'_full_name'" class="block text-sm font-medium text-[#1f2937]">{{ __('Full Name') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_full_name'" 
                                        name="full_name" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('full_name', $address->first_name . ' ' . $address->last_name) : ($address->first_name . ' ' . $address->last_name)" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_phone'" class="block text-sm font-medium text-[#1f2937]">{{ __('Phone Number') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_phone'" 
                                        name="phone" 
                                        type="tel" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('phone', $address->phone) : $address->phone" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_email'" class="block text-sm font-medium text-[#1f2937]">{{ __('Email') }} <span class="text-[#6b7280] text-xs">({{ __('optional') }})</span></label>
                                    <input 
                                        :id="'address_'.$address->id.'_email'" 
                                        name="email" 
                                        type="email" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('email', $address->email) : $address->email" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_line_one'" class="block text-sm font-medium text-[#1f2937]">{{ __('House/Flat/Building No') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_line_one'" 
                                        name="line_one" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('line_one', $address->line_one) : $address->line_one" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('line_one')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_line_two'" class="block text-sm font-medium text-[#1f2937]">{{ __('Street / Area') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_line_two'" 
                                        name="line_two" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('line_two', $address->line_two) : $address->line_two" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('line_two')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_city'" class="block text-sm font-medium text-[#1f2937]">{{ __('City') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_city'" 
                                        name="city" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('city', $address->city) : $address->city" 
                                        required 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_postal_code'" class="block text-sm font-medium text-[#1f2937]">{{ __('Postal Code') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_postal_code'" 
                                        name="postal_code" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('postal_code', $address->postal_code) : $address->postal_code" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_state'" class="block text-sm font-medium text-[#1f2937]">{{ __('Province') }}</label>
                                    <input 
                                        :id="'address_'.$address->id.'_state'" 
                                        name="state" 
                                        type="text" 
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10" 
                                        :value="$isEditing ? old('state', $address->state) : $address->state" 
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                                </div>

                                <div>
                                    <label :for="'address_'.$address->id.'_additional_notes'" class="block text-sm font-medium text-[#1f2937]">{{ __('Additional Notes') }} <span class="text-[#6b7280] text-xs">({{ __('optional') }})</span></label>
                                    <textarea 
                                        :id="'address_'.$address->id.'_additional_notes'" 
                                        name="additional_notes" 
                                        rows="3"
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm text-[#111827] focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                    >{{ $isEditing ? old('additional_notes', $address->meta['additional_notes'] ?? '') : ($address->meta['additional_notes'] ?? '') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('additional_notes')" />
                                </div>

                                <input type="hidden" name="country_code" :value="$address->country_code">

                                <div class="flex items-center gap-3 pt-4">
                                    <button type="submit" class="rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition">
                                        {{ __('Save Address') }}
                                    </button>
                                    <button type="button" onclick="window.location.href='{{ route('cart.index') }}'" class="rounded-full border border-[#111827] bg-white px-6 py-2.5 text-sm font-semibold text-[#111827] hover:bg-[#f9fafb] transition">
                                        {{ __('Proceed') }}
                                    </button>
                                </div>
                            </form>
                        </details>

                        <form method="post" action="{{ route('profile.addresses.destroy', $address) }}" class="mt-4 flex justify-end">
                            @csrf
                            @method('delete')
                            <button
                                type="submit"
                                class="text-sm font-semibold text-red-600 hover:text-red-500"
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


