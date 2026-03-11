<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                📦 {{ __('Produtos') }}
            </h2>

            <a href="{{ route('produtos.create') }}" class="ml-auto inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition duration-150">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Novo Produto') }}
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

            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">

                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Lista de Produtos
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Descrição
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Preço
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($produtos as $produto)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            #{{ $produto->id }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $produto->nome }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $produto->descricao }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ number_format($produto->preco, 2, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('produtos.edit', $produto->id) }}" class="inline-flex items-center text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition">Editar</a>
                                            <form id="form-del-produto-{{ $produto->id }}" method="POST" action="{{ route('produtos.destroy', $produto->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    data-delete-form="form-del-produto-{{ $produto->id }}"
                                                    data-delete-info="{{ json_encode(['Nome' => $produto->nome, 'Descrição' => $produto->descricao ?: '-', 'Preço' => 'R$ ' . number_format($produto->preco, 2, ',', '.')]) }}"
                                                    class="inline-flex items-center text-sm font-semibold bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
