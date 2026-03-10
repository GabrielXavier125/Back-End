<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                👥 {{ __('Cadastrar Novo Cliente') }}
            </h2>
        </div>
    </x-slot>

    <!-- Formulario apontando para a rota de salvar -->
     <form action="{{ route('clientes.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Campos do formulário -->

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Cadastro de Cliente
                    </h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('clientes.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar à lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('clientes.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="nome" :value="__('Nome')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone')" />
                            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                        </div>

                        <div>
                            <x-input-label for="cpf" :value="__('CPF')" />
                            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf')" />
                            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                        </div>

                        <div>
                            <x-input-label for="endereco" :value="__('Endereço')" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" :value="old('endereco')" />
                            <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
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
