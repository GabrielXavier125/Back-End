<x-app-layout title="Alunos">
<div class="py-4">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Alunos</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $students->total() }} aluno(s) encontrado(s)</p>
        </div>
        @can('create', App\Models\Student::class)
        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Aluno
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-700 mb-1">Pesquisar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou matrícula..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="min-w-40">
            <label class="block text-xs font-medium text-gray-700 mb-1">Turma</label>
            <select name="classroom_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas</option>
                @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
        <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-2 py-2">Limpar</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($students->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Nenhum aluno encontrado.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Nome</th>
                        <th class="px-5 py-3 text-left">Matrícula</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Responsável</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium text-gray-900">{{ $student->name }}</div>
                            @if($student->hasPendingRelease())
                            <span class="text-xs text-yellow-600 font-medium">Liberação pendente</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $student->registration }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $student->classroom->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">
                            @if($student->guardian_name)
                            <div>{{ $student->guardian_name }}</div>
                            <div class="text-xs text-gray-400">{{ $student->guardian_phone }}</div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($student->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Ver</a>
                                @can('update', $student)
                                <a href="{{ route('students.edit', $student) }}" class="text-gray-600 hover:text-gray-800 text-xs font-medium">Editar</a>
                                @endcan
                                @if(auth()->user()->isTeacher())
                                <a href="{{ route('early-releases.create', ['student_id' => $student->id]) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Liberar</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
</x-app-layout>
