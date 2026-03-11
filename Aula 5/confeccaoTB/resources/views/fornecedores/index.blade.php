<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                🏭 {{ __('Fornecedores') }}
            </h2>

            <a href="{{ route('fornecedores.create') }}" class="ml-auto inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition duration-150">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Novo Fornecedor') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="js-success-alert mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline"> {{ session('success') }}</span>
                </div>
            @endif

            @if($fornecedores->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="mt-4 text-gray-500 text-sm">Nenhum fornecedor cadastrado ainda.</p>
                    <a href="{{ route('fornecedores.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        Adicionar o primeiro fornecedor
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($fornecedores as $fornecedor)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-200 flex flex-col">
                            <div class="p-5 flex-1">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">#{{ $fornecedor->id }}</span>
                                    <span class="text-2xl">🏭</span>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-3">{{ $fornecedor->nome }}</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="truncate">{{ $fornecedor->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span>{{ $fornecedor->telefone }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ $fornecedor->cnpj }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="truncate">{{ $fornecedor->endereco }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="{{ route('fornecedores.edit', $fornecedor->id) }}" class="inline-flex items-center gap-1 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </a>
                                <form id="form-del-fornecedor-{{ $fornecedor->id }}" method="POST" action="{{ route('fornecedores.destroy', $fornecedor->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        data-delete-form="form-del-fornecedor-{{ $fornecedor->id }}"
                                        data-delete-info="{{ json_encode(['Nome' => $fornecedor->nome, 'Email' => $fornecedor->email, 'Telefone' => $fornecedor->telefone, 'CNPJ' => $fornecedor->cnpj, 'Endereço' => $fornecedor->endereco]) }}"
                                        class="inline-flex items-center gap-1 text-sm font-semibold bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
