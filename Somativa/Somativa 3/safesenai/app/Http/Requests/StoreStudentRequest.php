<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Student::class);
    }

    public function rules(): array
    {
        return [
            'classroom_id'   => ['required', 'exists:classrooms,id'],
            'name'           => ['required', 'string', 'max:255'],
            'registration'   => ['required', 'string', 'max:50', 'unique:students,registration'],
            'birth_date'     => ['nullable', 'date'],
            'guardian_name'  => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'guardian_email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'classroom_id'   => 'turma',
            'name'           => 'nome',
            'registration'   => 'matrícula',
            'birth_date'     => 'data de nascimento',
            'guardian_name'  => 'nome do responsável',
            'guardian_phone' => 'telefone do responsável',
            'guardian_email' => 'e-mail do responsável',
        ];
    }
}
