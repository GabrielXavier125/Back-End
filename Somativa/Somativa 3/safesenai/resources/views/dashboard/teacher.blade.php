<x-app-layout title="Dashboard Professor">
<div class="py-4">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Saídas para Confirmar</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['early_releases_waiting'] }}</div>
            @if($stats['early_releases_waiting'] > 0)
            <div class="text-xs text-yellow-500 mt-1 font-medium">Aguardando confirmação</div>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Saídas Confirmadas Hoje</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['early_confirmed_today'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Entradas para Confirmar</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['late_entries_waiting'] }}</div>
            @if($stats['late_entries_waiting'] > 0)
            <div class="text-xs text-blue-500 mt-1 font-medium">Confirmar presença</div>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-indigo-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Presenças Confirmadas</div>
            <div class="text-3xl font-bold text-indigo-600">{{ $stats['late_confirmed_today'] }}</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-3 mb-6">
        @if($stats['early_releases_waiting'] > 0)
        <a href="{{ route('early-releases.index', ['status' => 'waiting_teacher']) }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
            Confirmar Saídas ({{ $stats['early_releases_waiting'] }})
        </a>
        @endif
        @if($stats['late_entries_waiting'] > 0)
        <a href="{{ route('late-entries.index', ['status' => 'waiting_teacher']) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Confirmar Entradas ({{ $stats['late_entries_waiting'] }})
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Pending Early Releases --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b bg-yellow-50 flex items-center justify-between">
                <h2 class="font-semibold text-yellow-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Saídas Aguardando Confirmação
                    @if($pendingEarlyReleases->count() > 0)
                    <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingEarlyReleases->count() }}</span>
                    @endif
                </h2>
                <a href="{{ route('early-releases.index') }}" class="text-sm text-yellow-700 hover:underline">Ver todas</a>
            </div>
            @if($pendingEarlyReleases->isEmpty())
            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Nenhuma saída pendente de confirmação.
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($pendingEarlyReleases as $release)
                <div class="px-5 py-4 flex items-start justify-between gap-3 hover:bg-gray-50">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900">{{ $release->student->name }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">
                            {{ $release->student->classroom->name ?? 'Sem turma' }}
                            @if(!empty($release->missed_periods)) • <span class="text-yellow-600 font-medium">{{ $release->missed_periods_label }}</span>@endif
                            • {{ $release->created_at->format('H:i') }}
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Motivo: {{ $release->reason }}</div>
                    </div>
                    <form method="POST" action="{{ route('early-releases.confirm-teacher', $release) }}" onsubmit="return confirm('Confirmar que {{ $release->student->name }} saiu da sala?')">
                        @csrf
                        <button type="submit" class="flex-shrink-0 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Confirmar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Pending Late Entries --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b bg-blue-50 flex items-center justify-between">
                <h2 class="font-semibold text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Entradas Atrasadas Aguardando
                    @if($pendingLateEntries->count() > 0)
                    <span class="bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingLateEntries->count() }}</span>
                    @endif
                </h2>
                <a href="{{ route('late-entries.index') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
            </div>
            @if($pendingLateEntries->isEmpty())
            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Nenhuma entrada pendente de confirmação.
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($pendingLateEntries as $entry)
                <div class="px-5 py-4 flex items-start justify-between gap-3 hover:bg-gray-50">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900">{{ $entry->student->name }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">
                            {{ $entry->student->classroom->name ?? 'Sem turma' }} •
                            Chegou às {{ $entry->arrived_at?->format('H:i') ?? $entry->created_at->format('H:i') }}
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Motivo: {{ $entry->reason }}</div>
                    </div>
                    <form method="POST" action="{{ route('late-entries.confirm', $entry) }}" onsubmit="return confirm('Confirmar presença de {{ $entry->student->name }} em sala?')">
                        @csrf
                        <button type="submit" class="flex-shrink-0 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Confirmar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>
</x-app-layout>
