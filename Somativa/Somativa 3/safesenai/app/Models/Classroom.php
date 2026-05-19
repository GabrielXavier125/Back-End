<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'grade',
        'shift',
        'year',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'year' => 'integer',
        ];
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'teacher');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function getShiftLabelAttribute(): string
    {
        return match ($this->shift) {
            'morning'   => 'Manhã',
            'afternoon' => 'Tarde',
            'evening'   => 'Noite',
            default     => $this->shift,
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} - {$this->grade} ({$this->shiftLabel})";
    }
}
