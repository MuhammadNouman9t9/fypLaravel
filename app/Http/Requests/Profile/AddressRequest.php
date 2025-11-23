<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^[0-9+\-\s\(\)]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'line_one' => ['required', 'string', 'max:255'],
            'line_two' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:64'],
            'state' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string', 'max:1000'],
            'country_code' => ['required', 'string', 'size:2'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Split full_name into first_name and last_name
        if ($this->has('full_name') && !$this->has('first_name')) {
            $fullName = trim($this->input('full_name'));
            $nameParts = explode(' ', $fullName, 2);
            
            $this->merge([
                'first_name' => $nameParts[0] ?? $fullName,
                'last_name' => $nameParts[1] ?? '',
            ]);
        }
    }
}
