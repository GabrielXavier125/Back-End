<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EarlyRelease extends Model
{
    protected $fillable = [
        'student_id',
        'coordinator_id',
        'teacher_id',
        'gatekeeper_id',
        'reason',
        'observation',
        'status',
        'missed_periods',
        'released_at',
        'teacher_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'released_at'          => 'datetime',
            'teacher_confirmed_at' => 'datetime',
            'missed_periods'       => 'array',
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

    public function gatekeeper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gatekeeper_id');
    }

    public function getMissedPeriodsLabelAttribute(): ?string
    {
        if (empty($this->missed_periods)) return null;
        $sorted = $this->missed_periods;
        sort($sorted);
        return implode(', ', array_map(fn($p) => $p . 'ª', $sorted)) . ' Aula';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting_teacher' => 'Aguardando Professor',
            'waiting_gate'    => 'Aguardando Portaria',
            'released'        => 'Liberado',
            'cancelled'       => 'Cancelado',
            default           => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'waiting_teacher' => 'blue',
            'waiting_gate'    => 'yellow',
            'released'        => 'green',
            'cancelled'       => 'red',
            default           => 'gray',
        };
    }

    public function scopeWaitingTeacher($query)
    {
        return $query->where('status', 'waiting_teacher');
    }

    public function scopeWaitingGate($query)
    {
        return $query->where('status', 'waiting_gate');
    }

    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
