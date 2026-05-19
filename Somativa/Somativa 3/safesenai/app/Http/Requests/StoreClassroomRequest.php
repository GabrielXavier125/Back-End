<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'coordinator';
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:100'],
            'grade' => ['required', 'string', 'max:50'],
            'shift' => ['required', 'in:morning,afternoon,evening'],
            'year'  => ['required', 'integer', 'min:2020', 'max:2099'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => 'nome da turma',
            'grade' => 'série/ano',
            'shift' => 'turno',
            'year'  => 'ano letivo',
        ];
    }
}
