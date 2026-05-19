<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEarlyReleaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'coordinator';
    }

    public function rules(): array
    {
        return [
            'student_id'    => ['required', 'exists:students,id'],
            'reason'        => ['required', 'string', 'max:255'],
            'observation'   => ['nullable', 'string', 'max:1000'],
            'missed_periods'   => ['required', 'array', 'min:1'],
            'missed_periods.*' => ['integer', 'between:1,5'],
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id'    => 'aluno',
            'reason'        => 'motivo',
            'observation'   => 'observação',
            'missed_periods' => 'aulas perdidas',
        ];
    }
}
