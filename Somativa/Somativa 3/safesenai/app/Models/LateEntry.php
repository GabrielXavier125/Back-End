<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LateEntry extends Model
{
    protected $fillable = [
        'student_id',
        'coordinator_id',
        'teacher_id',
        'reason',
        'observation',
        'status',
        'missed_periods',
        'arrived_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'arrived_at'     => 'datetime',
            'confirmed_at'   => 'datetime',
            'missed_periods' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting_teacher' => 'Aguardando Professor',
            'confirmed'       => 'Confirmado',
            'cancelled'       => 'Cancelado',
            default           => $this->status,
        };
    }

    public function getMissedPeriodsLabelAttribute(): ?string
    {
        if (empty($this->missed_periods)) return null;
        $sorted = $this->missed_periods;
        sort($sorted);
        return implode(', ', array_map(fn($p) => $p . 'ª', $sorted)) . ' Aula';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'waiting_teacher' => 'blue',
            'confirmed'       => 'green',
            'cancelled'       => 'red',
            default           => 'gray',
        };
    }

    public function scopeWaitingTeacher($query)
    {
        return $query->where('status', 'waiting_teacher');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
