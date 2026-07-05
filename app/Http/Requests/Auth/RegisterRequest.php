<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If an unverified registration already exists for this email, let the
        // user resubmit the form (with the same email/phone/cnic) so the OTP
        // step can simply be resent instead of failing on "already registered".
        $unverifiedUserId = User::query()
            ->where('email', $this->input('email'))
            ->whereNull('email_verified_at')
            ->value('id');

        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255',
                Rule::unique(User::class)->ignore($unverifiedUserId),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:32',
                'regex:/^\+92[0-9]{10}$/',
                Rule::unique(User::class)->ignore($unverifiedUserId),
            ],
            'avatar' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'cnic' => [
                'required',
                'string',
                'size:13',
                'regex:/^[0-9]{13}$/',
                Rule::unique(User::class)->ignore($unverifiedUserId),
            ],
            'preferred_language' => [
                'nullable',
                'string',
                'max:8',
                Rule::in(['en', 'ur']),
            ],
            'timezone' => [
                'nullable',
                'string',
                'max:64',
            ],
            'study_program' => [
                'required',
                'string',
                Rule::in(['BSCS', 'BSSE', 'BSIT', 'BBA', 'OTHER']),
            ],
            'about_me' => [
                'required',
                'string',
                'max:2000',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                // At least 1 uppercase letter and 1 special character
                'regex:/^(?=.*[A-Z])(?=.*[^A-Za-z0-9]).+$/',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name cannot exceed 50 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'last_name.max' => 'Last name cannot exceed 50 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.regex' => 'Phone must be in format +92XXXXXXXXXX.',
            'phone.unique' => 'This phone number is already registered.',
            'avatar.required' => 'Display picture is required.',
            'avatar.mimes' => 'Display picture must be a JPG or PNG.',
            'avatar.max' => 'Display picture cannot be larger than 2MB.',
            'cnic.required' => 'CNIC is required.',
            'cnic.size' => 'CNIC must be exactly 13 digits.',
            'cnic.regex' => 'CNIC must contain only digits.',
            'cnic.unique' => 'This CNIC is already registered.',
            'preferred_language.in' => 'Please select a valid language.',
            'study_program.required' => 'Study program is required.',
            'study_program.in' => 'Please select a valid study program.',
            'about_me.required' => 'About Me is required.',
            'about_me.max' => 'About Me cannot exceed 2000 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include at least 1 uppercase letter and 1 special character.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cnic')) {
            $this->merge([
                'cnic' => preg_replace('/[^0-9]/', '', (string) $this->cnic),
            ]);
        }

        if ($this->has('phone')) {
            $this->merge([
                'phone' => trim((string) $this->phone) ?: null,
            ]);
        }

        if ($this->has('timezone')) {
            $this->merge([
                'timezone' => trim((string) $this->timezone) ?: null,
            ]);
        }
    }
}
