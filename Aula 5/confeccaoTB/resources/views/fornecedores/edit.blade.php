<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                🏭 {{ __('Editar Fornecedor') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Atualização de Cadastro
                    </h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('fornecedores.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar à lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('fornecedores.update', $fornecedor->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="nome" :value="__('Nome')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $fornecedor->nome)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $fornecedor->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone', $fornecedor->telefone)" maxlength="15" inputmode="numeric" placeholder="(11) 99999-9999" />
                            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                        </div>

                        <div>
                            <x-input-label for="cnpj" :value="__('CNPJ')" />
                            <x-text-input id="cnpj" name="cnpj" type="text" class="mt-1 block w-full" :value="old('cnpj', $fornecedor->cnpj)" maxlength="18" inputmode="numeric" placeholder="00.000.000/0000-00" required />
                            <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
                        </div>

                        <div>
                            <x-input-label for="endereco" :value="__('Endereço')" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" :value="old('endereco', $fornecedor->endereco)" />
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
            const cnpj = document.getElementById('cnpj');

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

            function formatCnpj(value) {
                const digits = onlyDigits(value).slice(0, 14);

                if (digits.length <= 2) {
                    return digits;
                }

                if (digits.length <= 5) {
                    return `${digits.slice(0, 2)}.${digits.slice(2)}`;
                }

                if (digits.length <= 8) {
                    return `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5)}`;
                }

                if (digits.length <= 12) {
                    return `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8)}`;
                }

                return `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8, 12)}-${digits.slice(12)}`;
            }

            if (telefone) {
                telefone.addEventListener('input', function () {
                    this.value = formatTelefone(this.value);
                });
                telefone.value = formatTelefone(telefone.value);
            }

            if (cnpj) {
                cnpj.addEventListener('input', function () {
                    this.value = formatCnpj(this.value);
                });
                cnpj.value = formatCnpj(cnpj.value);
            }
        })();
    </script>
</x-app-layout>
