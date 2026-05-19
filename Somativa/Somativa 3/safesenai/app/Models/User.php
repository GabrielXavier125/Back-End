<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'classroom_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isGatekeeper(): bool
    {
        return $this->role === 'gatekeeper';
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function releasesAsCoordinator(): HasMany
    {
        return $this->hasMany(EarlyRelease::class, 'coordinator_id');
    }

    public function releasesAsTeacher(): HasMany
    {
        return $this->hasMany(EarlyRelease::class, 'teacher_id');
    }

    public function releasesAsGatekeeper(): HasMany
    {
        return $this->hasMany(EarlyRelease::class, 'gatekeeper_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'coordinator' => 'Coordenação',
            'teacher'     => 'Professor',
            'gatekeeper'  => 'Porteiro',
            default       => 'Desconhecido',
        };
    }
}
