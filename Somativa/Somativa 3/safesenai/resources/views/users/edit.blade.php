<x-app-layout title="Editar Usuário">
<div class="py-4 max-w-lg">
    <div class="mb-5">
        <a href="{{ route('users.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Editar Usuário</h2>
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4" x-data="{ role: '{{ old('role', $user->role) }}' }">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Perfil *</label>
                <select name="role" required x-model="role" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="teacher" @selected(old('role', $user->role) === 'teacher')>Professor</option>
                    <option value="gatekeeper" @selected(old('role', $user->role) === 'gatekeeper')>Porteiro</option>
                </select>
            </div>
            <div x-show="role === 'teacher'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Turma Vinculada</label>
                <select name="classroom_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sem turma vinculada</option>
                    @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(old('classroom_id', $user->classroom_id) == $classroom->id)>{{ $classroom->name }} — {{ $classroom->shiftLabel }}</option>
                    @endforeach
                </select>
                @error('classroom_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha <span class="text-gray-400">(deixe em branco para manter)</span></label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Salvar</button>
                <a href="{{ route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition-colors">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
