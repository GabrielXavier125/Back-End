<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                🧮 {{ __('Cadastrar Novo Item de Estoque') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Cadastro de Estoque
                    </h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('estoques.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar a lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('estoques.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="produto_id" :value="__('Produto')" />
                            <select id="produto_id" name="produto_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um produto</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                                        {{ $produto->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('produto_id')" />
                        </div>

                        <div>
                            <x-input-label for="quantidade" :value="__('Quantidade')" />
                            <x-text-input id="quantidade" name="quantidade" type="number" min="0" class="mt-1 block w-full" :value="old('quantidade')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('quantidade')" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Salvar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
