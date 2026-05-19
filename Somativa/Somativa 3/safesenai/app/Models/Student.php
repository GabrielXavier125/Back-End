<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'classroom_id',
        'name',
        'registration',
        'birth_date',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'active' => 'boolean',
        ];
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function earlyReleases(): HasMany
    {
        return $this->hasMany(EarlyRelease::class);
    }

    public function lateEntries(): HasMany
    {
        return $this->hasMany(LateEntry::class);
    }

    public function hasPendingRelease(): bool
    {
        return $this->earlyReleases()->whereIn('status', ['waiting_teacher', 'waiting_gate'])->exists();
    }

    public function hasPendingLateEntry(): bool
    {
        return $this->lateEntries()->where('status', 'waiting_teacher')->exists();
    }
}
