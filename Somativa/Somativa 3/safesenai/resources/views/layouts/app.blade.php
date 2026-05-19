<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SAFE' }} - Sistema de Autorização e Fluxo Escolar</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="flex flex-col w-64 bg-blue-900 text-white flex-shrink-0 transition-all duration-300">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
            <div class="w-9 h-9 bg-yellow-400 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div class="text-lg font-bold leading-none">SAFE</div>
                <div class="text-xs text-blue-300 mt-0.5">Fluxo Escolar</div>
            </div>
        </div>

        {{-- User info --}}
        <div class="px-4 py-3 border-b border-blue-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-blue-300">{{ auth()->user()->role_label }}</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('dashboard'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('dashboard')])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            {{-- Liberações — accordion para professor, link simples para porteiro --}}
            @if(auth()->user()->isTeacher())
            @php
                $teacherClassroomId = auth()->user()->classroom_id;
                $pendingReleases = $teacherClassroomId
                    ? \App\Models\EarlyRelease::waitingTeacher()->whereHas('student', fn($q) => $q->where('classroom_id', $teacherClassroomId))->count()
                    : 0;
            @endphp
            <div x-data="{ open: {{ request()->routeIs('early-releases.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('early-releases.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="flex-1 text-left">Liberações</span>
                    @if($pendingReleases > 0)
                    <span class="bg-yellow-400 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingReleases }}</span>
                    @endif
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l border-blue-700 space-y-1">
                    <a href="{{ route('early-releases.index') }}" @class(['flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-colors', 'bg-blue-600 text-white font-medium' => request()->routeIs('early-releases.index'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('early-releases.index')])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Ver Liberações
                    </a>
                </div>
            </div>
            @endif

            @if(auth()->user()->isGatekeeper())
            <a href="{{ route('early-releases.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('early-releases.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('early-releases.*')])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Liberações
            </a>
            @endif

            {{-- Liberações com accordion para coordenador --}}
            @if(auth()->user()->role === 'coordinator')
            @php $pendingReleasesCoord = \App\Models\EarlyRelease::waitingTeacher()->count() + \App\Models\EarlyRelease::waitingGate()->count(); @endphp
            <div x-data="{ open: {{ request()->routeIs('early-releases.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('early-releases.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="flex-1 text-left">Liberações</span>
                    @if($pendingReleasesCoord > 0)
                    <span class="bg-yellow-400 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingReleasesCoord }}</span>
                    @endif
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l border-blue-700 space-y-1">
                    <a href="{{ route('early-releases.index') }}" @class(['flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-colors', 'bg-blue-600 text-white font-medium' => request()->routeIs('early-releases.index'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('early-releases.index')])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Ver Liberações
                    </a>
                    <a href="{{ route('early-releases.create') }}" @class(['flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-colors', 'bg-yellow-500 text-white font-medium' => request()->routeIs('early-releases.create'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('early-releases.create')])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Registrar Saída
                    </a>
                </div>
            </div>
            @endif

            {{-- Entrada Atrasada - Coordenação e Professor --}}
            @if(in_array(auth()->user()->role, ['coordinator', 'teacher']))
            @php
                if (auth()->user()->isTeacher()) {
                    $teacherClassroomId = auth()->user()->classroom_id;
                    $pendingLate = $teacherClassroomId
                        ? \App\Models\LateEntry::waitingTeacher()->whereHas('student', fn($q) => $q->where('classroom_id', $teacherClassroomId))->count()
                        : 0;
                } else {
                    $pendingLate = \App\Models\LateEntry::waitingTeacher()->count();
                }
            @endphp
            <div x-data="{ open: {{ request()->routeIs('late-entries.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('late-entries.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="flex-1 text-left">Entrada Atrasada</span>
                    @if($pendingLate > 0)
                    <span class="bg-orange-400 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingLate }}</span>
                    @endif
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l border-blue-700 space-y-1">
                    <a href="{{ route('late-entries.index') }}" @class(['flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-colors', 'bg-blue-600 text-white font-medium' => request()->routeIs('late-entries.index'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('late-entries.index')])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Ver Entradas
                    </a>
                    @if(auth()->user()->role === 'coordinator')
                    <a href="{{ route('late-entries.create') }}" @class(['flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-colors', 'bg-orange-500 text-white font-medium' => request()->routeIs('late-entries.create'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('late-entries.create')])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Registrar Atraso
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Coordenação --}}
            @if(auth()->user()->role === 'coordinator')
            <div class="pt-2">
                <div class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-1">Administração</div>

                <a href="{{ route('students.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('students.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('students.*')])>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Alunos
                </a>

                <a href="{{ route('classrooms.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('classrooms.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('classrooms.*')])>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Turmas
                </a>

                <a href="{{ route('users.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('users.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('users.*')])>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Usuários
                </a>

                <a href="{{ route('reports.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('reports.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('reports.*')])>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Relatórios
                </a>

                <a href="{{ route('audit-logs.index') }}" @class(['flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors', 'bg-blue-700 text-white' => request()->routeIs('audit-logs.*'), 'text-blue-200 hover:bg-blue-800 hover:text-white' => !request()->routeIs('audit-logs.*')])>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Logs de Auditoria
                </a>
            </div>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-3 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-blue-200 hover:bg-blue-800 hover:text-white transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white shadow-sm z-10 flex items-center justify-between px-6 h-14">
            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('sidebar').classList.toggle('w-0')" class="text-gray-500 hover:text-gray-700 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'SAFE' }}</h1>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>{{ now()->format('d/m/Y H:i') }}</span>
                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Meu Perfil</a>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-4" role="alert">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-4" role="alert">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
