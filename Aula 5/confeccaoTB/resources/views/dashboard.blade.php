<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Clientes</h3>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $clientesTotal }}</p>
                    <div class="mt-4 space-y-1 text-sm text-gray-600">
                        <p>Com telefone: <span class="font-semibold text-gray-900">{{ $clientesComTelefone }}</span></p>
                        <p>Sem telefone: <span class="font-semibold text-gray-900">{{ $clientesSemTelefone }}</span></p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-cyan-500">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Fornecedores</h3>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $fornecedoresTotal }}</p>
                    <div class="mt-4 space-y-1 text-sm text-gray-600">
                        <p>Com telefone: <span class="font-semibold text-gray-900">{{ $fornecedoresComTelefone }}</span></p>
                        <p>Sem telefone: <span class="font-semibold text-gray-900">{{ $fornecedoresSemTelefone }}</span></p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-violet-500">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Produtos</h3>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $produtosTotal }}</p>
                    <div class="mt-4 space-y-1 text-sm text-gray-600">
                        <p>Com estoque: <span class="font-semibold text-gray-900">{{ $produtosComEstoque }}</span></p>
                        <p>Sem estoque: <span class="font-semibold text-gray-900">{{ $produtosSemEstoque }}</span></p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Estoque</h3>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $estoqueItensTotal }} itens</p>
                    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-600">
                        <p>Registros: <span class="font-semibold text-gray-900">{{ $estoqueRegistros }}</span></p>
                        <p>Zerado: <span class="font-semibold text-red-600">{{ $estoqueZerado }}</span></p>
                        <p>Baixo (1-5): <span class="font-semibold text-amber-600">{{ $estoqueBaixo }}</span></p>
                        <p>Adequado (>5): <span class="font-semibold text-emerald-600">{{ $estoqueAdequado }}</span></p>
                    </div>
                    @if($estoquePorProduto->isNotEmpty())
                        <div class="mt-4 border-t border-gray-100 pt-3 space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Quantidade por produto</p>
                            @foreach($estoquePorProduto as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700 truncate max-w-[60%]">{{ $item->produto->nome ?? '—' }}</span>
                                    @if($item->quantidade == 0)
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">0</span>
                                    @elseif($item->quantidade <= 5)
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">{{ $item->quantidade }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">{{ $item->quantidade }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500 md:col-span-2 xl:col-span-2">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Pedidos</h3>
                            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $pedidosTotal }}</p>
                            <p class="text-sm text-gray-500">Total de pedidos cadastrados</p>

                            <div class="mt-4 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                                <p class="text-gray-700">Concluídos: <span class="font-semibold">{{ $pedidosConcluido }}</span></p>
                                <p class="text-gray-700">Pendentes: <span class="font-semibold">{{ $pedidosPendente }}</span></p>
                                <p class="text-gray-700">Em andamento: <span class="font-semibold">{{ $pedidosAndamento }}</span></p>
                                <p class="text-gray-700">Cancelados: <span class="font-semibold">{{ $pedidosCancelado }}</span></p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="w-36 h-36 rounded-full relative"
                                style="background: {{ $pedidoChartBackground }};"
                                title="Distribuição dos pedidos por status"
                            >
                                <div class="absolute inset-5 bg-white rounded-full"></div>
                            </div>
                            <div class="text-xs text-gray-600 grid grid-cols-2 gap-x-4 gap-y-1">
                                <p><span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span>Pendente</p>
                                <p><span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1"></span>Andamento</p>
                                <p><span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>Concluído</p>
                                <p><span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>Cancelado</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-rose-500">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Financeiro</h3>
                    <p class="mt-3 text-3xl font-bold text-gray-900">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-gray-500">Somatório do valor total de todos os pedidos</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
