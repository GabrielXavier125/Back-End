<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                👥 {{ __('Editar Cliente') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Atualizacao de Cadastro
                    </h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('clientes.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar a lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('clientes.update', $cliente->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="nome" :value="__('Nome')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $cliente->nome)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $cliente->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone', $cliente->telefone)" maxlength="15" inputmode="numeric" placeholder="(11) 99999-9999" required />
                            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                        </div>

                        <div>
                            <x-input-label for="cpf" :value="__('CPF')" />
                            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $cliente->cpf)" maxlength="14" inputmode="numeric" placeholder="000.000.000-00" required />
                            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                        </div>

                        <div>
                            <x-input-label for="endereco" :value="__('Endereco')" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" :value="old('endereco', $cliente->endereco)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const telefone = document.getElementById('telefone');
            const cpf = document.getElementById('cpf');

            function onlyDigits(value) {
                return value.replace(/\D/g, '');
            }

            function formatTelefone(value) {
                const digits = onlyDigits(value).slice(0, 11);

                if (digits.length <= 2) {
                    return digits.length ? `(${digits}` : '';
                }

                if (digits.length <= 6) {
                    return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
                }

                if (digits.length <= 10) {
                    return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`;
                }

                return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
            }

            function formatCpf(value) {
                const digits = onlyDigits(value).slice(0, 11);

                if (digits.length <= 3) {
                    return digits;
                }

                if (digits.length <= 6) {
                    return `${digits.slice(0, 3)}.${digits.slice(3)}`;
                }

                if (digits.length <= 9) {
                    return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
                }

                return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
            }

            if (telefone) {
                telefone.addEventListener('input', function () {
                    this.value = formatTelefone(this.value);
                });
                telefone.value = formatTelefone(telefone.value);
            }

            if (cpf) {
                cpf.addEventListener('input', function () {
                    this.value = formatCpf(this.value);
                });
                cpf.value = formatCpf(cpf.value);
            }
        })();
    </script>
</x-app-layout>
