<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EnableTwoFactorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // The secret is sourced from session (server-issued) — never trust a
        // client-submitted secret, so it is intentionally not validated here.
        return [
            'code' => ['required', 'string', 'size:6'],
        ];
    }
}
