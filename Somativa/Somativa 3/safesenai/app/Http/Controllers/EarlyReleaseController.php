<?php

namespace App\Http\Controllers;

use App\Events\StudentReleased;
use App\Http\Requests\StoreEarlyReleaseRequest;
use App\Models\AuditLog;
use App\Models\EarlyRelease;
use App\Models\Student;
use Illuminate\Http\Request;

class EarlyReleaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', EarlyRelease::class);

        $query = EarlyRelease::with(['student.classroom', 'coordinator', 'teacher', 'gatekeeper'])
            ->latest();

        if ($request->user()->isTeacher()) {
            $classroomId = $request->user()->classroom_id;
            if ($classroomId) {
                $query->whereHas('student', fn ($q) => $q->where('classroom_id', $classroomId));
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('student', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $releases = $query->paginate(15)->withQueryString();

        return view('early-releases.index', compact('releases'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', EarlyRelease::class);

        $selectedStudent = $request->get('student_id')
            ? Student::find($request->get('student_id'))
            : null;

        return view('early-releases.create', compact('selectedStudent'));
    }

    public function store(StoreEarlyReleaseRequest $request)
    {
        $student = Student::findOrFail($request->student_id);

        if ($student->hasPendingRelease()) {
            return back()->withErrors(['student_id' => 'Este aluno já possui uma autorização de saída pendente.'])->withInput();
        }

        $release = EarlyRelease::create([
            'student_id'     => $request->student_id,
            'coordinator_id' => $request->user()->id,
            'reason'         => $request->reason,
            'observation'    => $request->observation,
            'status'         => 'waiting_teacher',
            'missed_periods'  => $request->missed_periods,
        ]);

        AuditLog::record('early_release.created', $release, [], $release->toArray());

        return redirect()->route('early-releases.show', $release)
            ->with('success', 'Autorização de saída registrada! O aluno deve retornar à sala para o professor confirmar.');
    }

    public function show(EarlyRelease $earlyRelease)
    {
        $this->authorize('view', $earlyRelease);
        $earlyRelease->load(['student.classroom', 'coordinator', 'teacher', 'gatekeeper']);
        return view('early-releases.show', compact('earlyRelease'));
    }

    // Step 2: Teacher confirms the student left the classroom
    public function confirmTeacher(EarlyRelease $earlyRelease, Request $request)
    {
        $this->authorize('confirmTeacher', $earlyRelease);

        $earlyRelease->update([
            'teacher_id'           => $request->user()->id,
            'teacher_confirmed_at' => now(),
            'status'               => 'waiting_gate',
        ]);

        AuditLog::record('early_release.teacher_confirmed', $earlyRelease);

        return redirect()->back()
            ->with('success', "Saída de {$earlyRelease->student->name} confirmada. Aguardando portaria.");
    }

    // Step 3: Gatekeeper confirms the student physically left the building
    public function confirm(EarlyRelease $earlyRelease, Request $request)
    {
        $this->authorize('confirm', $earlyRelease);

        $earlyRelease->update([
            'status'        => 'released',
            'gatekeeper_id' => $request->user()->id,
            'released_at'   => now(),
        ]);

        AuditLog::record('early_release.confirmed', $earlyRelease);

        $earlyRelease->load(['student.classroom', 'coordinator', 'teacher', 'gatekeeper']);
        event(new StudentReleased($earlyRelease));

        return redirect()->route('dashboard')
            ->with('success', "Saída de {$earlyRelease->student->name} confirmada com sucesso!");
    }

    public function cancel(EarlyRelease $earlyRelease)
    {
        $this->authorize('cancel', $earlyRelease);

        $earlyRelease->update(['status' => 'cancelled']);

        AuditLog::record('early_release.cancelled', $earlyRelease);

        return back()->with('success', 'Autorização cancelada.');
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');

        $students = Student::with('classroom')
            ->where('active', true)
            ->where('name', 'like', "%{$term}%")
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map(fn ($s) => [
                'id'           => $s->id,
                'name'         => $s->name,
                'registration' => $s->registration,
                'classroom'    => $s->classroom?->name,
                'has_pending'  => $s->hasPendingRelease(),
            ]);

        return response()->json($students);
    }
}
