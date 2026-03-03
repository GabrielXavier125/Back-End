<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                👥 {{ __('Clientes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">

                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Lista de Clientes
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
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Telefone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    CPF
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Endereço
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($clientes as $cliente)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            #{{ $cliente->id }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $cliente->nome }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $cliente->email }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $cliente->telefone }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $cliente->cpf }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $cliente->endereco }}
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






<!-- {{--<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confecção</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($clientes as $cliente)
                        <div class="border p-4 border shadow-sm">
                            <h3 class="font-bold text-lg">{{ $cliente->nome }}</h3>
                            <p class="text-sm text-gray-600">{{ $cliente->email }}</p>
                            <p class="text-sm text-gray-600">{{ $cliente->telefone }}</p>
                            <p class="text-sm text-gray-600">{{ $cliente->cpf }}</p>
                            <p class="text-sm text-gray-600">{{ $cliente->endereco }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>--}} -->