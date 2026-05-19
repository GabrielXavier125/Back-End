<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Models\AuditLog;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students')->orderBy('name')->paginate(15);
        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.create');
    }

    public function store(StoreClassroomRequest $request)
    {
        $classroom = Classroom::create($request->validated());

        AuditLog::record('classroom.created', $classroom, [], $classroom->toArray());

        return redirect()->route('classrooms.index')
            ->with('success', 'Turma cadastrada com sucesso!');
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(StoreClassroomRequest $request, Classroom $classroom)
    {
        $old = $classroom->toArray();
        $classroom->update($request->validated());

        AuditLog::record('classroom.updated', $classroom, $old, $classroom->fresh()->toArray());

        return redirect()->route('classrooms.index')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->update(['active' => false]);
        AuditLog::record('classroom.deactivated', $classroom);

        return redirect()->route('classrooms.index')
            ->with('success', 'Turma desativada com sucesso!');
    }
}
