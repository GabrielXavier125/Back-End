<?php

namespace App\Http\Controllers;

use App\Models\EarlyRelease;
use App\Models\LateEntry;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'coordinator' => $this->coordinatorDashboard(),
            'teacher'     => $this->teacherDashboard($user),
            'gatekeeper'  => $this->gatekeeperDashboard(),
            default       => redirect()->route('login'),
        };
    }

    private function coordinatorDashboard()
    {
        $stats = [
            'total_today'        => EarlyRelease::today()->count(),
            'released_today'     => EarlyRelease::today()->released()->count(),
            'waiting_gate'       => EarlyRelease::waitingGate()->count(),
            'waiting_teacher'    => EarlyRelease::waitingTeacher()->count(),
            'late_entries_today' => LateEntry::today()->count(),
            'late_waiting'       => LateEntry::waitingTeacher()->count(),
            'total_students'     => Student::where('active', true)->count(),
        ];

        $recentReleases = EarlyRelease::with(['student.classroom', 'coordinator', 'teacher', 'gatekeeper'])
            ->today()
            ->latest()
            ->take(8)
            ->get();

        $recentLateEntries = LateEntry::with(['student.classroom', 'coordinator', 'teacher'])
            ->today()
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.coordinator', compact('stats', 'recentReleases', 'recentLateEntries'));
    }

    private function teacherDashboard(User $user)
    {
        $classroomId = $user->classroom_id;

        $byClassroom = fn ($q) => $classroomId
            ? $q->whereHas('student', fn ($s) => $s->where('classroom_id', $classroomId))
            : $q->whereRaw('0 = 1');

        $stats = [
            'early_releases_waiting' => $byClassroom(EarlyRelease::waitingTeacher())->count(),
            'early_confirmed_today'  => EarlyRelease::today()->where('teacher_id', $user->id)->count(),
            'late_entries_waiting'   => $byClassroom(LateEntry::waitingTeacher())->count(),
            'late_confirmed_today'   => LateEntry::today()->where('teacher_id', $user->id)->confirmed()->count(),
        ];

        $pendingEarlyReleases = $byClassroom(EarlyRelease::waitingTeacher())
            ->with(['student.classroom', 'coordinator'])
            ->latest()
            ->get();

        $pendingLateEntries = $byClassroom(LateEntry::waitingTeacher())
            ->with(['student.classroom', 'coordinator'])
            ->latest()
            ->get();

        return view('dashboard.teacher', compact('stats', 'pendingEarlyReleases', 'pendingLateEntries'));
    }

    private function gatekeeperDashboard()
    {
        $waiting = EarlyRelease::waitingGate()
            ->with(['student.classroom', 'coordinator', 'teacher'])
            ->latest()
            ->get();

        $confirmedToday = EarlyRelease::today()
            ->released()
            ->with(['student.classroom', 'teacher'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'waiting'         => $waiting->count(),
            'confirmed_today' => EarlyRelease::today()->released()->count(),
        ];

        return view('dashboard.gatekeeper', compact('waiting', 'confirmedToday', 'stats'));
    }
}
