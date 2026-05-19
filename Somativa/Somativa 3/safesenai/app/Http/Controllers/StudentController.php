<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::with('classroom')->orderBy('name');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration', 'like', "%{$search}%");
            });
        }

        if ($classroomId = $request->get('classroom_id')) {
            $query->where('classroom_id', $classroomId);
        }

        if ($request->get('active') !== null) {
            $query->where('active', $request->boolean('active'));
        }

        $students = $query->paginate(15)->withQueryString();
        $classrooms = Classroom::where('active', true)->orderBy('name')->get();

        return view('students.index', compact('students', 'classrooms'));
    }

    public function create()
    {
        $this->authorize('create', Student::class);
        $classrooms = Classroom::where('active', true)->orderBy('name')->get();
        return view('students.create', compact('classrooms'));
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());

        AuditLog::record('student.created', $student, [], $student->toArray());

        return redirect()->route('students.show', $student)
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['classroom', 'earlyReleases.teacher', 'earlyReleases.gatekeeper']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        $classrooms = Classroom::where('active', true)->orderBy('name')->get();
        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $old = $student->toArray();
        $student->update($request->validated());

        AuditLog::record('student.updated', $student, $old, $student->fresh()->toArray());

        return redirect()->route('students.show', $student)
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->update(['active' => false]);

        AuditLog::record('student.deactivated', $student);

        return redirect()->route('students.index')
            ->with('success', 'Aluno desativado com sucesso!');
    }
}
