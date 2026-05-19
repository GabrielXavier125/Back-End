<x-app-layout title="Logs de Auditoria">
<div class="py-4">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Logs de Auditoria</h2>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Ação</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="ex: student.created" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Data</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
        <a href="{{ route('audit-logs.index') }}" class="text-gray-500 text-sm px-2 py-2">Limpar</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($logs->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">Nenhum log encontrado.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Data/Hora</th>
                        <th class="px-5 py-3 text-left">Usuário</th>
                        <th class="px-5 py-3 text-left">Ação</th>
                        <th class="px-5 py-3 text-left">Modelo</th>
                        <th class="px-5 py-3 text-left">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $log->user?->name ?? 'Sistema' }}</td>
                        <td class="px-5 py-3">
                            <code class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $log->action }}</code>
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-xs">
                            @if($log->model_type)
                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            @else
                            —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
