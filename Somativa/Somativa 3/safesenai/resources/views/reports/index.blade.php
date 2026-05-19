<x-app-layout title="Relatórios">
<div class="py-4">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Relatórios de Saída</h2>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-5 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Data Início</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Data Fim</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="waiting_gate" @selected(($filters['status'] ?? '') === 'waiting_gate')>Aguardando Portaria</option>
                    <option value="released" @selected(($filters['status'] ?? '') === 'released')>Liberado</option>
                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Aluno</label>
                <select name="student_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" @selected(($filters['student_id'] ?? '') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Gerar Relatório</button>
            <a href="{{ route('reports.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition-colors">Limpar Filtros</a>
        </div>
    </form>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $releases->total() }}</div>
            <div class="text-xs text-gray-500 mt-1">Total</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['released'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Liberados</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['waiting'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Aguardando</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Cancelados</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($releases->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">Nenhum registro encontrado com os filtros aplicados.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Aluno</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Motivo</th>
                        <th class="px-5 py-3 text-left">Professor</th>
                        <th class="px-5 py-3 text-left">Porteiro</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Autorização</th>
                        <th class="px-5 py-3 text-left">Saída</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($releases as $release)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-400">{{ $release->id }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $release->student->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->student->classroom->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-700 max-w-xs truncate">{{ $release->reason }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->teacher->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->gatekeeper?->name ?? '—' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$release->status" /></td>
                        <td class="px-5 py-3 text-gray-500">{{ $release->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $release->released_at?->format('H:i') ?? '—' }}</td>
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
