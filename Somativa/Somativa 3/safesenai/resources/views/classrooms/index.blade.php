<x-app-layout title="Turmas">
<div class="py-4">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-800">Turmas</h2>
        <a href="{{ route('classrooms.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nova Turma
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($classrooms->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">Nenhuma turma cadastrada.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Nome</th>
                        <th class="px-5 py-3 text-left">Série/Ano</th>
                        <th class="px-5 py-3 text-left">Turno</th>
                        <th class="px-5 py-3 text-left">Ano Letivo</th>
                        <th class="px-5 py-3 text-left">Alunos</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($classrooms as $classroom)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $classroom->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $classroom->grade }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $classroom->shiftLabel }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $classroom->year }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $classroom->students_count }}</td>
                        <td class="px-5 py-3">
                            @if($classroom->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativa</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativa</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('classrooms.edit', $classroom) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</a>
                                <form method="POST" action="{{ route('classrooms.destroy', $classroom) }}" onsubmit="return confirm('Desativar esta turma?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Desativar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t">{{ $classrooms->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
