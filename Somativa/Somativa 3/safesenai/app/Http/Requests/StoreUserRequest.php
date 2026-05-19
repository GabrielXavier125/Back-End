<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'coordinator';
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'         => ['required', 'in:teacher,gatekeeper'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'         => 'nome',
            'email'        => 'e-mail',
            'role'         => 'perfil',
            'classroom_id' => 'turma',
            'password'     => 'senha',
        ];
    }
}
