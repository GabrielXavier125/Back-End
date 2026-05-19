<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLateEntryRequest;
use App\Models\AuditLog;
use App\Models\LateEntry;
use App\Models\Student;
use Illuminate\Http\Request;

class LateEntryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', LateEntry::class);

        $query = LateEntry::with(['student.classroom', 'coordinator', 'teacher'])
            ->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('student', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        if ($request->user()->isTeacher()) {
            $classroomId = $request->user()->classroom_id;
            if ($classroomId) {
                $query->whereHas('student', fn ($q) => $q->where('classroom_id', $classroomId));
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        $entries = $query->paginate(15)->withQueryString();

        return view('late-entries.index', compact('entries'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', LateEntry::class);

        $students = Student::with('classroom')
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $selectedStudent = $request->get('student_id')
            ? Student::find($request->get('student_id'))
            : null;

        return view('late-entries.create', compact('students', 'selectedStudent'));
    }

    public function store(StoreLateEntryRequest $request)
    {
        $student = Student::findOrFail($request->student_id);

        if ($student->hasPendingLateEntry()) {
            return back()
                ->withErrors(['student_id' => 'Este aluno já possui uma entrada atrasada pendente de confirmação.'])
                ->withInput();
        }

        $entry = LateEntry::create([
            'student_id'     => $request->student_id,
            'coordinator_id' => $request->user()->id,
            'reason'         => $request->reason,
            'observation'    => $request->observation,
            'status'         => 'waiting_teacher',
            'missed_periods'  => $request->missed_periods,
            'arrived_at'      => now(),
        ]);

        AuditLog::record('late_entry.created', $entry, [], $entry->toArray());

        return redirect()->route('late-entries.show', $entry)
            ->with('success', 'Autorização de entrada registrada com sucesso!');
    }

    public function show(LateEntry $lateEntry)
    {
        $this->authorize('view', $lateEntry);
        $lateEntry->load(['student.classroom', 'coordinator', 'teacher']);
        return view('late-entries.show', compact('lateEntry'));
    }

    public function confirm(LateEntry $lateEntry, Request $request)
    {
        $this->authorize('confirm', $lateEntry);

        $lateEntry->update([
            'status'       => 'confirmed',
            'teacher_id'   => $request->user()->id,
            'confirmed_at' => now(),
        ]);

        AuditLog::record('late_entry.confirmed', $lateEntry);

        return redirect()->route('dashboard')
            ->with('success', "Presença de {$lateEntry->student->name} confirmada com sucesso!");
    }

    public function cancel(LateEntry $lateEntry)
    {
        $this->authorize('cancel', $lateEntry);

        $lateEntry->update(['status' => 'cancelled']);

        AuditLog::record('late_entry.cancelled', $lateEntry);

        return back()->with('success', 'Autorização de entrada cancelada.');
    }
}
