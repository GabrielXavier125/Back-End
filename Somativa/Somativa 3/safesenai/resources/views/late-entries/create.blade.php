<x-app-layout title="Registrar Entrada Atrasada">
<div class="py-4 max-w-2xl">
    <div class="mb-5">
        <a href="{{ route('late-entries.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Registrar Entrada Atrasada</h2>
                <p class="text-sm text-gray-500">O aluno deverá aguardar a confirmação do professor para entrar em sala.</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800 my-4">
            <strong>Horário de chegada registrado:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>

        <form method="POST" action="{{ route('late-entries.store') }}" class="space-y-4">
            @csrf

            {{-- Student Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aluno *</label>
                <div class="relative">
                    <input
                        type="text"
                        id="student_search"
                        placeholder="Digite o nome do aluno..."
                        autocomplete="off"
                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                        value="{{ $selectedStudent ? $selectedStudent->name . ' - ' . $selectedStudent->registration : old('_student_name') }}"
                    >
                    <div id="student_results" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-56 overflow-y-auto"></div>
                </div>
                <input type="hidden" name="student_id" id="student_id" value="{{ $selectedStudent?->id ?? old('student_id') }}">
                @error('student_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                @if($selectedStudent)
                <div id="student_info" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm">
                    <div class="font-semibold text-blue-900">{{ $selectedStudent->name }}</div>
                    <div class="text-blue-700">{{ $selectedStudent->classroom->name ?? 'Turma não informada' }} • Matrícula: {{ $selectedStudent->registration }}</div>
                    @if($selectedStudent->hasPendingLateEntry())
                    <div class="text-yellow-700 font-medium mt-1">⚠ Este aluno já possui uma entrada atrasada pendente.</div>
                    @endif
                </div>
                @else
                <div id="student_info" class="hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm"></div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo do Atraso *</label>
                <input type="text" name="reason" value="{{ old('reason') }}" required
                    placeholder="ex: Transporte atrasado, Consulta médica, Problema familiar..."
                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror">
                @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observation" rows="3"
                    placeholder="Informações adicionais (opcional)..."
                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('observation') }}</textarea>
            </div>

            {{-- Missed Periods --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Aulas Perdidas *</label>
                <p class="text-xs text-gray-500 mb-3">Selecione todas as aulas que o aluno perdeu por chegar atrasado.</p>
                <div class="flex gap-2 flex-wrap">
                    @foreach(range(1, 5) as $period)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="missed_periods[]" value="{{ $period }}" class="sr-only peer"
                            @checked(is_array(old('missed_periods')) && in_array($period, old('missed_periods')))>
                        <span class="flex flex-col items-center justify-center w-16 h-16 rounded-xl border-2 border-gray-200 bg-white text-gray-600 text-sm font-semibold peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-700 hover:border-orange-300 hover:bg-orange-50 transition-all select-none">
                            <span class="text-xl font-bold leading-none">{{ $period }}º</span>
                            <span class="text-xs mt-0.5 font-normal">aula</span>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('missed_periods')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-sm text-orange-800">
                <strong>Como funciona:</strong> Ao registrar, o status ficará como <strong>"Aguardando Professor"</strong>.
                O aluno deverá se dirigir à sala de aula. O professor deverá confirmar a presença no sistema.
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                    Registrar Entrada Atrasada
                </button>
                <a href="{{ route('late-entries.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
const searchInput = document.getElementById('student_search');
const resultsDiv = document.getElementById('student_results');
const studentIdInput = document.getElementById('student_id');
const studentInfo = document.getElementById('student_info');
let debounceTimer;

searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const q = this.value.trim();
    if (q.length < 2) { resultsDiv.classList.add('hidden'); return; }

    debounceTimer = setTimeout(() => {
        fetch(`/api/students/search?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Nenhum aluno encontrado.</div>';
                } else {
                    data.forEach(s => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2.5 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 text-sm';
                        div.innerHTML = `
                            <div class="font-medium text-gray-900">${s.name}</div>
                            <div class="text-gray-500 text-xs">${s.classroom ?? 'Sem turma'} • Matrícula: ${s.registration}</div>
                        `;
                        div.addEventListener('click', () => {
                            searchInput.value = `${s.name} - ${s.registration}`;
                            studentIdInput.value = s.id;
                            resultsDiv.classList.add('hidden');
                            studentInfo.innerHTML = `
                                <div class="font-semibold text-blue-900">${s.name}</div>
                                <div class="text-blue-700">${s.classroom ?? 'Sem turma'} • Matrícula: ${s.registration}</div>
                            `;
                            studentInfo.classList.remove('hidden');
                        });
                        resultsDiv.appendChild(div);
                    });
                }
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
        resultsDiv.classList.add('hidden');
    }
});
</script>
</x-app-layout>
