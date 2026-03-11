<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                🧾 {{ __('Editar Pedido') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Atualização de Pedido</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('pedidos.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Voltar à lista</a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('pedidos.update', $pedido->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="cliente_id" :value="__('Cliente')" />
                            <select id="cliente_id" name="cliente_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $pedido->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('cliente_id')" />
                        </div>

                        <div>
                            <x-input-label for="produto_id" :value="__('Produto')" />
                            <select id="produto_id" name="produto_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um produto</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}" {{ old('produto_id', $pedido->produto_id) == $produto->id ? 'selected' : '' }}>
                                        {{ $produto->nome }} — R$ {{ number_format($produto->preco, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('produto_id')" />
                        </div>

                        <div>
                            <x-input-label for="quantidade" :value="__('Quantidade')" />
                            <x-text-input id="quantidade" name="quantidade" type="number" min="1" class="mt-1 block w-full" :value="old('quantidade', $pedido->quantidade)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('quantidade')" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pendente"     {{ old('status', $pedido->status) == 'pendente'     ? 'selected' : '' }}>Pendente</option>
                                <option value="em_andamento" {{ old('status', $pedido->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="concluido"    {{ old('status', $pedido->status) == 'concluido'    ? 'selected' : '' }}>Concluído</option>
                                <option value="cancelado"    {{ old('status', $pedido->status) == 'cancelado'    ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <p class="text-sm text-gray-400">* O valor total é recalculado automaticamente ao salvar (quantidade × preço do produto).</p>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Atualizar Pedido') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
