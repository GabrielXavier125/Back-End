<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('classroom')->where('role', '!=', 'coordinator')->orderBy('name');

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $users = $query->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $classrooms = Classroom::where('active', true)->orderBy('name')->get();
        return view('users.create', compact('classrooms'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'role'         => $request->role,
            'classroom_id' => $request->role === 'teacher' ? $request->classroom_id : null,
            'password'     => Hash::make($request->password),
        ]);

        AuditLog::record('user.created', $user, [], ['name' => $user->name, 'role' => $user->role]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $user)
    {
        $classrooms = Classroom::where('active', true)->orderBy('name')->get();
        return view('users.edit', compact('user', 'classrooms'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'role'         => ['required', 'in:teacher,gatekeeper'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
        ]);

        $old = $user->only(['name', 'email', 'role', 'classroom_id']);
        $user->update([
            ...$request->only(['name', 'email', 'role']),
            'classroom_id' => $request->role === 'teacher' ? $request->classroom_id : null,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['min:8', 'confirmed']]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        AuditLog::record('user.updated', $user, $old, $user->fresh()->only(['name', 'email', 'role', 'classroom_id']));

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->update(['active' => false]);
        AuditLog::record('user.deactivated', $user);

        return redirect()->route('users.index')
            ->with('success', 'Usuário desativado com sucesso!');
    }
}
