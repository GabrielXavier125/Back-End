<?php

namespace App\Policies;

use App\Models\LateEntry;
use App\Models\User;

class LateEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['coordinator', 'teacher']);
    }

    public function view(User $user, LateEntry $lateEntry): bool
    {
        if ($user->isCoordinator()) {
            return true;
        }
        if ($user->isTeacher()) {
            return $user->classroom_id !== null
                && $lateEntry->student->classroom_id === $user->classroom_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'coordinator';
    }

    public function confirm(User $user, LateEntry $lateEntry): bool
    {
        if ($user->role !== 'teacher' || $lateEntry->status !== 'waiting_teacher') {
            return false;
        }
        return $user->classroom_id !== null
            && $lateEntry->student->classroom_id === $user->classroom_id;
    }

    public function cancel(User $user, LateEntry $lateEntry): bool
    {
        return $user->role === 'coordinator' && $lateEntry->status === 'waiting_teacher';
    }
}
