<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait RegistrationValidationRules
{
    /**
     * Get the validation rules used to validate new user registrations.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function registrationRules(): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRulesForRegistration(),
            'phone' => $this->phoneRulesForRegistration(),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails during registration.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRulesForRegistration(): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique(User::class),
        ];
    }

    /**
     * Get the validation rules used to validate user phone numbers during registration.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function phoneRulesForRegistration(): array
    {
        return ['required', 'string', 'regex:/^(\+\d{1,3}[- ]?)?\d{10,}$/', 'max:20'];
    }
}
