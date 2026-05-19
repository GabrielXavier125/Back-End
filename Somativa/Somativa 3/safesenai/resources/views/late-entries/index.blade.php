<x-app-layout title="Entradas Atrasadas">
<div class="py-4">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Entradas Atrasadas</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $entries->total() }} registro(s)</p>
        </div>
        @can('create', App\Models\LateEntry::class)
        <a href="{{ route('late-entries.create') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar Entrada Atrasada
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-700 mb-1">Aluno</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do aluno..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos</option>
                <option value="waiting_teacher" @selected(request('status') === 'waiting_teacher')>Aguardando Professor</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmado</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Data</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
        <a href="{{ route('late-entries.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-2 py-2">Limpar</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($entries->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Nenhuma entrada atrasada encontrada.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Aluno</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Motivo do Atraso</th>
                        <th class="px-5 py-3 text-left">Coordenação</th>
                        <th class="px-5 py-3 text-left">Professor</th>
                        <th class="px-5 py-3 text-left">Aula</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Chegada</th>
                        <th class="px-5 py-3 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($entries as $entry)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-900">
                            <a href="{{ route('late-entries.show', $entry) }}" class="hover:text-blue-600">{{ $entry->student->name }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $entry->student->classroom->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $entry->reason }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $entry->coordinator->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $entry->teacher?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if(!empty($entry->missed_periods))
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_values(array_unique((array)$entry->missed_periods)) as $p)
                                <span class="inline-flex items-center bg-orange-50 text-orange-700 border border-orange-200 text-xs font-semibold px-1.5 py-0.5 rounded">{{ $p }}ª</span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3"><x-late-status-badge :status="$entry->status" /></td>
                        <td class="px-5 py-3 text-gray-500">{{ $entry->arrived_at?->format('d/m/Y H:i') ?? $entry->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($entry->status === 'waiting_teacher' && auth()->user()->isTeacher())
                                <form method="POST" action="{{ route('late-entries.confirm', $entry) }}" onsubmit="return confirm('Confirmar presença de {{ $entry->student->name }}?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-semibold">Confirmar</button>
                                </form>
                                @endif
                                @if($entry->status === 'waiting_teacher' && auth()->user()->isCoordinator())
                                <form method="POST" action="{{ route('late-entries.cancel', $entry) }}" onsubmit="return confirm('Cancelar esta autorização?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Cancelar</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t">{{ $entries->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
