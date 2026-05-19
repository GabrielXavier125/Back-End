<x-app-layout title="Detalhes da Entrada Atrasada">
<div class="py-4 max-w-2xl">
    <div class="mb-5">
        <a href="{{ route('late-entries.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Entradas Atrasadas
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Entrada Atrasada #{{ $lateEntry->id }}</h2>
            <x-late-status-badge :status="$lateEntry->status" />
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aluno</div>
                    <div class="font-semibold text-gray-900">{{ $lateEntry->student->name }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $lateEntry->student->classroom->name ?? '—' }} •
                        Matrícula: {{ $lateEntry->student->registration }}
                    </div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Autorizado por</div>
                    <div class="font-semibold text-gray-900">{{ $lateEntry->coordinator->name }}</div>
                    <div class="text-sm text-gray-500">Coordenação</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Motivo do Atraso</div>
                    <div class="text-gray-900">{{ $lateEntry->reason }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aula Perdida</div>
                    @if(!empty($lateEntry->missed_periods))
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_values(array_unique((array)$lateEntry->missed_periods)) as $p)
                        <span class="inline-flex items-center bg-orange-50 border border-orange-200 text-orange-800 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $p }}ª Aula</span>
                        @endforeach
                    </div>
                    @else
                    <div class="text-gray-400 text-sm">Não informado</div>
                    @endif
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Horário de Chegada</div>
                    <div class="text-gray-900">{{ $lateEntry->arrived_at?->format('d/m/Y H:i') ?? $lateEntry->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($lateEntry->observation)
                <div class="sm:col-span-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Observações</div>
                    <div class="text-gray-700">{{ $lateEntry->observation }}</div>
                </div>
                @endif
                @if($lateEntry->teacher)
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Professor que Confirmou</div>
                    <div class="font-semibold text-gray-900">{{ $lateEntry->teacher->name }}</div>
                    <div class="text-sm text-gray-500">Confirmado às {{ $lateEntry->confirmed_at?->format('H:i') }}</div>
                </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="border-t pt-4">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Linha do Tempo</div>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        </div>
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">Chegada registrada pela Coordenação</div>
                            <div class="text-gray-500">{{ $lateEntry->arrived_at?->format('d/m/Y H:i') ?? $lateEntry->created_at->format('d/m/Y H:i') }} • {{ $lateEntry->coordinator->name }}</div>
                        </div>
                    </div>
                    @if($lateEntry->status === 'confirmed')
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        </div>
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">Presença confirmada pelo Professor</div>
                            <div class="text-gray-500">{{ $lateEntry->confirmed_at?->format('d/m/Y H:i') }} • {{ $lateEntry->teacher?->name }}</div>
                        </div>
                    </div>
                    @elseif($lateEntry->status === 'waiting_teacher')
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                        </div>
                        <div class="text-sm text-blue-700 font-medium">Aguardando confirmação do professor...</div>
                    </div>
                    @elseif($lateEntry->status === 'cancelled')
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        </div>
                        <div class="text-sm text-red-600 font-medium">Autorização cancelada</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            @if($lateEntry->status === 'waiting_teacher')
            <div class="flex gap-3 pt-2 border-t">
                @can('confirm', $lateEntry)
                <form method="POST" action="{{ route('late-entries.confirm', $lateEntry) }}" onsubmit="return confirm('Confirmar presença de {{ $lateEntry->student->name }} em sala?')">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirmar Presença em Sala
                    </button>
                </form>
                @endcan
                @can('cancel', $lateEntry)
                <form method="POST" action="{{ route('late-entries.cancel', $lateEntry) }}" onsubmit="return confirm('Cancelar esta autorização?')">
                    @csrf
                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                        Cancelar Autorização
                    </button>
                </form>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
