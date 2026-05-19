<x-app-layout title="Detalhes do Aluno">
<div class="py-4">
    <div class="mb-5">
        <a href="{{ route('students.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Alunos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Student Info --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">Dados do Aluno</h2>
                    @can('update', $student)
                    <a href="{{ route('students.edit', $student) }}" class="text-xs text-blue-600 hover:underline">Editar</a>
                    @endcan
                </div>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Nome</div>
                        <div class="font-semibold text-gray-900 mt-0.5">{{ $student->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Matrícula</div>
                        <div class="text-gray-700 mt-0.5">{{ $student->registration }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Turma</div>
                        <div class="text-gray-700 mt-0.5">{{ $student->classroom->name ?? '—' }} {{ $student->classroom?->grade }}</div>
                    </div>
                    @if($student->birth_date)
                    <div>
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Nascimento</div>
                        <div class="text-gray-700 mt-0.5">{{ $student->birth_date->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Status</div>
                        <div class="mt-0.5">
                            @if($student->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-3 text-sm">Responsável</h3>
                @if($student->guardian_name)
                <div class="space-y-2 text-sm">
                    <div class="font-medium text-gray-900">{{ $student->guardian_name }}</div>
                    @if($student->guardian_phone)
                    <div class="text-gray-600">{{ $student->guardian_phone }}</div>
                    @endif
                    @if($student->guardian_email)
                    <div class="text-gray-600">{{ $student->guardian_email }}</div>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-400">Não informado.</p>
                @endif
            </div>

            @if(auth()->user()->isTeacher() && $student->active)
            <a href="{{ route('early-releases.create', ['student_id' => $student->id]) }}" class="flex items-center justify-center gap-2 w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Registrar Liberação
            </a>
            @endif
        </div>

        {{-- Release History --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b">
                    <h2 class="font-semibold text-gray-800">Histórico de Saídas ({{ $student->earlyReleases->count() }})</h2>
                </div>
                @if($student->earlyReleases->isEmpty())
                <div class="px-5 py-10 text-center text-gray-400 text-sm">Nenhuma saída registrada.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                            <tr>
                                <th class="px-5 py-3 text-left">Data</th>
                                <th class="px-5 py-3 text-left">Motivo</th>
                                <th class="px-5 py-3 text-left">Professor</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Saída</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($student->earlyReleases as $release)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-gray-500">{{ $release->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3 text-gray-700 max-w-xs truncate">{{ $release->reason }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $release->teacher->name }}</td>
                                <td class="px-5 py-3"><x-status-badge :status="$release->status" /></td>
                                <td class="px-5 py-3 text-gray-500">{{ $release->released_at?->format('H:i') ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
