<?php

namespace App\Policies;

use App\Models\EarlyRelease;
use App\Models\User;

class EarlyReleasePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['coordinator', 'teacher', 'gatekeeper']);
    }

    public function view(User $user, EarlyRelease $release): bool
    {
        if ($user->isCoordinator() || $user->isGatekeeper()) {
            return true;
        }
        if ($user->isTeacher()) {
            return $user->classroom_id !== null
                && $release->student->classroom_id === $user->classroom_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'coordinator';
    }

    public function confirmTeacher(User $user, EarlyRelease $release): bool
    {
        if ($user->role !== 'teacher' || $release->status !== 'waiting_teacher') {
            return false;
        }
        return $user->classroom_id !== null
            && $release->student->classroom_id === $user->classroom_id;
    }

    public function confirm(User $user, EarlyRelease $release): bool
    {
        return $user->role === 'gatekeeper' && $release->status === 'waiting_gate';
    }

    public function cancel(User $user, EarlyRelease $release): bool
    {
        if (!in_array($release->status, ['waiting_teacher', 'waiting_gate'])) {
            return false;
        }
        return $user->role === 'coordinator';
    }
}
