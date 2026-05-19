<x-app-layout title="Nova Turma">
<div class="py-4 max-w-lg">
    <div class="mb-5">
        <a href="{{ route('classrooms.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Cadastrar Nova Turma</h2>
        <form method="POST" action="{{ route('classrooms.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Turma *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="ex: Turma A" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Série/Ano *</label>
                <input type="text" name="grade" value="{{ old('grade') }}" placeholder="ex: 1º Ano EM" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turno *</label>
                    <select name="shift" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione...</option>
                        <option value="morning" @selected(old('shift') === 'morning')>Manhã</option>
                        <option value="afternoon" @selected(old('shift') === 'afternoon')>Tarde</option>
                        <option value="evening" @selected(old('shift') === 'evening')>Noite</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ano Letivo *</label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="2020" max="2099" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Cadastrar Turma</button>
                <a href="{{ route('classrooms.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition-colors">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
