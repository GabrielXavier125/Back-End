<x-app-layout title="Painel da Portaria">
<div class="py-4">

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aguardando Confirmação</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['waiting'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Confirmadas Hoje</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['confirmed_today'] }}</div>
        </div>
    </div>

    {{-- Pending --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b bg-yellow-50">
            <h2 class="font-semibold text-yellow-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Liberações Aguardando Confirmação
                @if($waiting->count() > 0)
                <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $waiting->count() }}</span>
                @endif
            </h2>
        </div>
        @if($waiting->isEmpty())
        <div class="px-5 py-10 text-center text-gray-400 text-sm">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Nenhuma liberação pendente.
        </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($waiting as $release)
            <div class="px-5 py-4 flex items-start justify-between gap-4 hover:bg-gray-50 transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900">{{ $release->student->name }}</div>
                    <div class="text-sm text-gray-500 mt-0.5">
                        {{ $release->student->classroom->name ?? 'Turma não informada' }} •
                        Prof. {{ $release->teacher->name }} •
                        {{ $release->created_at->format('H:i') }}
                    </div>
                    <div class="text-sm text-gray-700 mt-1 font-medium">Motivo: {{ $release->reason }}</div>
                    @if($release->observation)
                    <div class="text-sm text-gray-500 mt-0.5 italic">{{ $release->observation }}</div>
                    @endif
                </div>
                <form method="POST" action="{{ route('early-releases.confirm', $release) }}" onsubmit="return confirm('Confirmar saída de {{ $release->student->name }}?')">
                    @csrf
                    <button type="submit" class="flex-shrink-0 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirmar Saída
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Confirmed Today --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Saídas Confirmadas Hoje</h2>
        </div>
        @if($confirmedToday->isEmpty())
        <div class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma confirmação realizada hoje.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Aluno</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Motivo</th>
                        <th class="px-5 py-3 text-left">Saída</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($confirmedToday as $release)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $release->student->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->student->classroom->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $release->reason }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $release->released_at?->format('H:i') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
</x-app-layout>
