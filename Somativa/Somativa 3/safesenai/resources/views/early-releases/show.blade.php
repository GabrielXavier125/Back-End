<x-app-layout title="Detalhes da Liberação">
<div class="py-4 max-w-2xl">
    <div class="mb-5">
        <a href="{{ route('early-releases.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Liberações
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Autorização de Saída #{{ $earlyRelease->id }}</h2>
            <x-status-badge :status="$earlyRelease->status" />
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aluno</div>
                    <div class="font-semibold text-gray-900">{{ $earlyRelease->student->name }}</div>
                    <div class="text-sm text-gray-500">{{ $earlyRelease->student->classroom->name ?? '—' }} • {{ $earlyRelease->student->registration }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Registrado por</div>
                    <div class="font-semibold text-gray-900">{{ $earlyRelease->coordinator?->name ?? '—' }}</div>
                    <div class="text-sm text-gray-500">Coordenação • {{ $earlyRelease->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Motivo</div>
                    <div class="text-gray-900">{{ $earlyRelease->reason }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aula Perdida</div>
                    @if(!empty($earlyRelease->missed_periods))
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_values(array_unique((array)$earlyRelease->missed_periods)) as $p)
                        <span class="inline-flex items-center bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $p }}ª Aula</span>
                        @endforeach
                    </div>
                    @else
                    <div class="text-gray-400 text-sm">Não informado</div>
                    @endif
                </div>
                @if($earlyRelease->observation)
                <div class="col-span-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Observações</div>
                    <div class="text-gray-700">{{ $earlyRelease->observation }}</div>
                </div>
                @endif
            </div>

            {{-- 3-step Timeline --}}
            <div class="border-t pt-4">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Linha do Tempo</div>
                <div class="space-y-4">

                    {{-- Step 1: Coordination created --}}
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">Saída registrada pela Coordenação</div>
                            <div class="text-gray-500">{{ $earlyRelease->created_at->format('d/m/Y H:i') }} • {{ $earlyRelease->coordinator?->name }}</div>
                        </div>
                    </div>

                    {{-- Step 2: Teacher confirmation --}}
                    @if($earlyRelease->status === 'waiting_teacher')
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse"></div>
                        </div>
                        <div class="text-sm text-blue-700 font-medium pt-1.5">Aguardando confirmação do professor em sala...</div>
                    </div>
                    @else
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full {{ $earlyRelease->status === 'cancelled' ? 'bg-red-100' : 'bg-green-100' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                            @if($earlyRelease->status === 'cancelled')
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <div class="text-sm">
                            @if($earlyRelease->status === 'cancelled')
                            <div class="font-medium text-red-600">Autorização cancelada pela Coordenação</div>
                            @else
                            <div class="font-medium text-gray-900">Saída confirmada pelo Professor em sala</div>
                            <div class="text-gray-500">{{ $earlyRelease->teacher_confirmed_at?->format('d/m/Y H:i') }} • {{ $earlyRelease->teacher?->name }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Step 3: Gatekeeper confirmation --}}
                    @if(in_array($earlyRelease->status, ['waiting_gate', 'released']))
                        @if($earlyRelease->status === 'waiting_gate')
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-pulse"></div>
                            </div>
                            <div class="text-sm text-yellow-700 font-medium pt-1.5">Aguardando confirmação da portaria...</div>
                        </div>
                        @else
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">Saída confirmada pela Portaria</div>
                                <div class="text-gray-500">{{ $earlyRelease->released_at?->format('d/m/Y H:i') }} • {{ $earlyRelease->gatekeeper?->name }}</div>
                            </div>
                        </div>
                        @endif
                    @endif

                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3 pt-2 border-t">
                @can('confirmTeacher', $earlyRelease)
                <form method="POST" action="{{ route('early-releases.confirm-teacher', $earlyRelease) }}" onsubmit="return confirm('Confirmar que {{ $earlyRelease->student->name }} saiu da sala?')">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirmar Saída da Sala
                    </button>
                </form>
                @endcan

                @can('confirm', $earlyRelease)
                <form method="POST" action="{{ route('early-releases.confirm', $earlyRelease) }}" onsubmit="return confirm('Confirmar saída de {{ $earlyRelease->student->name }}?')">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                        Confirmar Saída da Escola
                    </button>
                </form>
                @endcan

                @can('cancel', $earlyRelease)
                <form method="POST" action="{{ route('early-releases.cancel', $earlyRelease) }}" onsubmit="return confirm('Cancelar esta autorização?')">
                    @csrf
                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                        Cancelar Autorização
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
</x-app-layout>
