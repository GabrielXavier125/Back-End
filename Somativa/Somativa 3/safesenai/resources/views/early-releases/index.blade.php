<x-app-layout title="Liberações">
<div class="py-4">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Liberações de Saída</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $releases->total() }} registro(s)</p>
        </div>
        @can('create', App\Models\EarlyRelease::class)
        <a href="{{ route('early-releases.create') }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nova Liberação
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
                <option value="waiting_gate" @selected(request('status') === 'waiting_gate')>Aguardando Portaria</option>
                <option value="released" @selected(request('status') === 'released')>Liberado</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Data</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
        <a href="{{ route('early-releases.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-2 py-2">Limpar</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($releases->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">Nenhuma liberação encontrada.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Aluno</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Motivo</th>
                        <th class="px-5 py-3 text-left">Aula</th>
                        <th class="px-5 py-3 text-left">Professor</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Data</th>
                        <th class="px-5 py-3 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($releases as $release)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-900">
                            <a href="{{ route('early-releases.show', $release) }}" class="hover:text-blue-600">{{ $release->student->name }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->student->classroom->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $release->reason }}</td>
                        <td class="px-5 py-3">
                            @if(!empty($release->missed_periods))
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_values(array_unique((array)$release->missed_periods)) as $p)
                                <span class="inline-flex items-center bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-semibold px-1.5 py-0.5 rounded">{{ $p }}ª</span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->teacher?->name ?? '—' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$release->status" /></td>
                        <td class="px-5 py-3 text-gray-500">{{ $release->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($release->status === 'waiting_teacher' && auth()->user()->isTeacher())
                                <form method="POST" action="{{ route('early-releases.confirm-teacher', $release) }}" onsubmit="return confirm('Confirmar saída de {{ $release->student->name }} da sala?')">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Confirmar Saída</button>
                                </form>
                                @endif
                                @if($release->status === 'waiting_gate' && auth()->user()->isGatekeeper())
                                <form method="POST" action="{{ route('early-releases.confirm', $release) }}" onsubmit="return confirm('Confirmar saída da escola?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-semibold">Liberar</button>
                                </form>
                                @endif
                                @if(in_array($release->status, ['waiting_teacher', 'waiting_gate']) && auth()->user()->isCoordinator())
                                <form method="POST" action="{{ route('early-releases.cancel', $release) }}" onsubmit="return confirm('Cancelar autorização?')">
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
        <div class="px-5 py-4 border-t">{{ $releases->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
