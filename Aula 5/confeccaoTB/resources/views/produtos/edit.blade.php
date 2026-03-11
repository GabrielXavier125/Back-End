<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                📦 {{ __('Editar Produto') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Atualização de Produto</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('produtos.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar à lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('produtos.update', $produto->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="nome" :value="__('Nome')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $produto->nome)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <div>
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <textarea id="descricao" name="descricao" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descricao', $produto->descricao) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        <div>
                            <x-input-label for="preco" :value="__('Preço (R$)')" />
                            <x-text-input id="preco" name="preco" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('preco', $produto->preco)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('preco')" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Atualizar') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
