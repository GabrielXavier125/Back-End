<x-app-layout title="Usuários">
<div class="py-4">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-800">Usuários do Sistema</h2>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Usuário
        </a>
    </div>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-700 mb-1">Pesquisar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Perfil</label>
            <select name="role" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos</option>
                <option value="teacher" @selected(request('role') === 'teacher')>Professor</option>
                <option value="gatekeeper" @selected(request('role') === 'gatekeeper')>Porteiro</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filtrar</button>
        <a href="{{ route('users.index') }}" class="text-gray-500 text-sm px-2 py-2">Limpar</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($users->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400">Nenhum usuário encontrado.</div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Nome</th>
                    <th class="px-5 py-3 text-left">E-mail</th>
                    <th class="px-5 py-3 text-left">Perfil</th>
                    <th class="px-5 py-3 text-left">Turma</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        @if($user->role === 'teacher')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Professor</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Porteiro</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-600 text-sm">
                        @if($user->role === 'teacher' && $user->classroom)
                        <span class="font-medium">{{ $user->classroom->name }}</span>
                        <span class="text-gray-400 text-xs ml-1">{{ $user->classroom->shiftLabel }}</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($user->active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</a>
                            @if($user->active)
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Desativar usuário?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Desativar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $users->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
