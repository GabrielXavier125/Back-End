<?php

namespace App\Http\Controllers;

use App\Models\EarlyRelease;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = EarlyRelease::with(['student.classroom', 'teacher', 'gatekeeper'])
            ->latest();

        $filters = $request->only(['date_from', 'date_to', 'status', 'student_id', 'teacher_id']);

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }

        $releases = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => $query->count(),
            'released'     => (clone $query)->where('status', 'released')->count(),
            'waiting'      => (clone $query)->where('status', 'waiting_gate')->count(),
            'cancelled'    => (clone $query)->where('status', 'cancelled')->count(),
        ];

        $students = Student::where('active', true)->orderBy('name')->get(['id', 'name']);

        return view('reports.index', compact('releases', 'filters', 'stats', 'students'));
    }
}
