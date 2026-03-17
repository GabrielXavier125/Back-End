<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Confecção TB') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:600,700|figtree:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(180,83,9,0.20),_transparent_28%),radial-gradient(circle_at_85%_15%,_rgba(8,145,178,0.16),_transparent_20%),linear-gradient(135deg,_#f7f1e8_0%,_#efe3d3_45%,_#e8d8ca_100%)]"></div>
            <div class="absolute -top-24 left-[-8rem] h-72 w-72 rounded-full bg-amber-300/20 blur-3xl"></div>
            <div class="absolute top-40 right-[-6rem] h-64 w-64 rounded-full bg-cyan-300/20 blur-3xl"></div>

            <header class="relative z-10 w-full px-6 py-6 lg:px-10">
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-flex items-center rounded-full border border-stone-900/15 bg-white/70 px-5 py-2 text-sm font-medium text-stone-900 shadow-sm backdrop-blur transition hover:border-stone-900/30 hover:bg-white"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center rounded-full border border-transparent px-5 py-2 text-sm font-medium text-stone-800 transition hover:border-stone-900/15 hover:bg-white/60"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center rounded-full border border-stone-900/15 bg-white/70 px-5 py-2 text-sm font-medium text-stone-900 shadow-sm backdrop-blur transition hover:border-stone-900/30 hover:bg-white"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <main class="relative z-10 mx-auto max-w-7xl px-6 pb-16 pt-4 lg:px-10 lg:pb-24 lg:pt-6">
                <section class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                    <div>
                        <div class="inline-flex items-center rounded-full border border-amber-800/15 bg-white/65 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-amber-800 backdrop-blur">
                            Loja de Tecidos e Confecção
                        </div>

                        <h1 class="mt-6 max-w-3xl font-['Playfair_Display'] text-5xl leading-tight text-stone-900 sm:text-6xl lg:text-7xl">
                            Tecidos, texturas e pedidos em um painel feito para a sua produção.
                        </h1>

                        <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-700">
                            Organize clientes, fornecedores, estoque, produtos e pedidos em um ambiente pensado para rotina de costura, acabamento e controle comercial de uma loja de tecidos.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <div class="rounded-2xl border border-white/70 bg-white/75 px-5 py-4 shadow-lg shadow-stone-900/5 backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Curadoria</p>
                                <p class="mt-2 text-2xl font-semibold text-stone-900">Algodão, linho, sarja e malha</p>
                            </div>
                            <div class="rounded-2xl border border-white/70 bg-white/75 px-5 py-4 shadow-lg shadow-stone-900/5 backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Fluxo</p>
                                <p class="mt-2 text-2xl font-semibold text-stone-900">Pedido, corte e entrega</p>
                            </div>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-stone-900/10 bg-stone-950 px-5 py-6 text-stone-50 shadow-xl shadow-stone-900/10">
                                <p class="text-xs uppercase tracking-[0.25em] text-stone-400">Estoque vivo</p>
                                <p class="mt-3 text-3xl font-semibold">Controle real</p>
                                <p class="mt-2 text-sm leading-6 text-stone-300">Acompanhe entradas e saídas por produto com mais clareza.</p>
                            </div>
                            <div class="rounded-3xl border border-stone-900/10 bg-white/80 px-5 py-6 shadow-lg shadow-stone-900/5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Cadastro rápido</p>
                                <p class="mt-3 text-3xl font-semibold text-stone-900">Dados limpos</p>
                                <p class="mt-2 text-sm leading-6 text-stone-600">Telefone, CPF e CNPJ já saem formatados na operação diária.</p>
                            </div>
                            <div class="rounded-3xl border border-stone-900/10 bg-amber-100/80 px-5 py-6 shadow-lg shadow-amber-900/10 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.25em] text-amber-800">Pedidos</p>
                                <p class="mt-3 text-3xl font-semibold text-stone-900">Fluxo claro</p>
                                <p class="mt-2 text-sm leading-6 text-stone-700">Visualize status, quantidades e andamento dos pedidos sem ruído.</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 translate-x-4 translate-y-4 rounded-[2rem] bg-stone-900/10 blur-2xl"></div>
                        <div class="relative overflow-hidden rounded-[2rem] border border-white/70 bg-white/70 shadow-2xl shadow-stone-900/10 backdrop-blur">
                            <div class="border-b border-stone-900/10 px-6 py-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Coleção da Semana</p>
                                        <h2 class="mt-2 font-['Playfair_Display'] text-3xl text-stone-900">Mostruário de Tecidos</h2>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="h-3 w-3 rounded-full bg-rose-300"></span>
                                        <span class="h-3 w-3 rounded-full bg-amber-300"></span>
                                        <span class="h-3 w-3 rounded-full bg-cyan-300"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-4 p-6">
                                <div class="grid grid-cols-[1.1fr_0.9fr] gap-4">
                                    <div class="rounded-[1.75rem] bg-[linear-gradient(135deg,_#7c2d12_0%,_#b45309_32%,_#f59e0b_100%)] p-6 text-white">
                                        <p class="text-xs uppercase tracking-[0.22em] text-amber-100">Linho Premium</p>
                                        <p class="mt-3 text-3xl font-semibold">Toque natural e caimento leve</p>
                                        <div class="mt-6 flex gap-2">
                                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs">Cru</span>
                                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs">Areia</span>
                                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs">Terracota</span>
                                        </div>
                                    </div>
                                    <div class="grid gap-4">
                                        <div class="rounded-[1.5rem] bg-[linear-gradient(160deg,_#164e63_0%,_#0f766e_100%)] p-5 text-white">
                                            <p class="text-xs uppercase tracking-[0.2em] text-cyan-100">Sarja</p>
                                            <p class="mt-2 text-xl font-semibold">Estrutura para peças duráveis</p>
                                        </div>
                                        <div class="rounded-[1.5rem] bg-[linear-gradient(145deg,_#5b412f_0%,_#8b5e34_100%)] p-5 text-white">
                                            <p class="text-xs uppercase tracking-[0.2em] text-amber-100">Malha</p>
                                            <p class="mt-2 text-xl font-semibold">Conforto para moda casual</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[1.75rem] border border-stone-900/10 bg-stone-50/90 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Paleta têxtil</p>
                                            <p class="mt-1 text-lg font-semibold text-stone-900">Tons que remetem a fibras, teares e costura</p>
                                        </div>
                                        <div class="hidden sm:flex gap-2">
                                            <span class="h-10 w-10 rounded-2xl bg-[#7c2d12]"></span>
                                            <span class="h-10 w-10 rounded-2xl bg-[#d97706]"></span>
                                            <span class="h-10 w-10 rounded-2xl bg-[#0f766e]"></span>
                                            <span class="h-10 w-10 rounded-2xl bg-[#8b5e34]"></span>
                                        </div>
                                    </div>
                                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                                            <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Atendimento</p>
                                            <p class="mt-1 text-sm leading-6 text-stone-700">Receba o cliente, consulte pedidos e confirme disponibilidade do estoque.</p>
                                        </div>
                                        <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                                            <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Produção</p>
                                            <p class="mt-1 text-sm leading-6 text-stone-700">Mantenha o fluxo da confecção conectado do cadastro à entrega.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
