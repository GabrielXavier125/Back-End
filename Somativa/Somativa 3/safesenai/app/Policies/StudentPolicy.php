<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['coordinator', 'teacher', 'gatekeeper']);
    }

    public function view(User $user, Student $student): bool
    {
        return in_array($user->role, ['coordinator', 'teacher', 'gatekeeper']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'coordinator';
    }

    public function update(User $user, Student $student): bool
    {
        return $user->role === 'coordinator';
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->role === 'coordinator';
    }
}
