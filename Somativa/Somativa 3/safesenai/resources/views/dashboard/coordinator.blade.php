<x-app-layout title="Dashboard">
<div class="py-4">

    {{-- Stats Row 1: Saída Antecipada --}}
    <div class="mb-2">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Saída Antecipada — Hoje</h3>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total de Saídas</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_today'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Liberados</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['released_today'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-400">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aguard. Professor</div>
            <div class="text-3xl font-bold text-blue-500">{{ $stats['waiting_teacher'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aguard. Portaria</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['waiting_gate'] }}</div>
        </div>
    </div>

    {{-- Stats Row 2: Entrada Atrasada --}}
    <div class="mb-2">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Entrada Atrasada — Hoje</h3>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-orange-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total de Entradas Atrasadas</div>
            <div class="text-3xl font-bold text-orange-600">{{ $stats['late_entries_today'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-400">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aguardando Professor</div>
            <div class="text-3xl font-bold text-blue-500">{{ $stats['late_waiting'] }}</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('late-entries.create') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Registrar Entrada Atrasada
        </a>
        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Aluno
        </a>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Relatórios
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        {{-- Recent Releases --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Saídas Antecipadas Hoje</h2>
                <a href="{{ route('early-releases.index') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
            </div>
            @if($recentReleases->isEmpty())
            <div class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma saída hoje.</div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($recentReleases as $release)
                <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50">
                    <div class="min-w-0">
                        <div class="font-medium text-gray-900 text-sm">
                            <a href="{{ route('early-releases.show', $release) }}" class="hover:text-blue-600">{{ $release->student->name }}</a>
                        </div>
                        <div class="text-xs text-gray-500">{{ $release->student->classroom->name ?? '—' }} • {{ $release->teacher?->name ?? 'Aguard. professor' }} • {{ $release->created_at->format('H:i') }}</div>
                    </div>
                    <x-status-badge :status="$release->status" />
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Recent Late Entries --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Entradas Atrasadas Hoje</h2>
                <a href="{{ route('late-entries.index') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
            </div>
            @if($recentLateEntries->isEmpty())
            <div class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma entrada atrasada hoje.</div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($recentLateEntries as $entry)
                <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50">
                    <div class="min-w-0">
                        <div class="font-medium text-gray-900 text-sm">
                            <a href="{{ route('late-entries.show', $entry) }}" class="hover:text-blue-600">{{ $entry->student->name }}</a>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $entry->student->classroom->name ?? '—' }} •
                            {{ $entry->arrived_at?->format('H:i') ?? $entry->created_at->format('H:i') }}
                            @if($entry->teacher) • Prof. {{ $entry->teacher->name }}@endif
                        </div>
                    </div>
                    <x-late-status-badge :status="$entry->status" />
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
