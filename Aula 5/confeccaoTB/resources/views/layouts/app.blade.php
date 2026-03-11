<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    <!-- Modal de Confirmação de Exclusão -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-pulse-once">
            <div class="p-6">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 bg-red-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Confirmar Exclusão</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Você tem certeza que deseja excluir este item? Esta ação não poderá ser desfeita.</p>
                    </div>
                </div>

                <div id="delete-modal-info" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-1 mb-5 divide-y divide-gray-100 text-sm"></div>

                <div class="flex justify-end gap-3">
                    <button id="delete-modal-cancel" type="button"
                        class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <button id="delete-modal-confirm" type="button"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Sim, Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var pendingFormId = null;

        function closeModal() {
            var modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingFormId = null;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-success-alert').forEach(function (alert) {
                setTimeout(function () {
                    alert.classList.add('transition', 'duration-500', 'opacity-0');
                    setTimeout(function () {
                        alert.remove();
                    }, 500);
                }, 3000);
            });

            document.querySelectorAll('[data-delete-form]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pendingFormId = this.dataset.deleteForm;
                    var info = {};
                    try { info = JSON.parse(this.dataset.deleteInfo || '{}'); } catch (e) {}

                    var container = document.getElementById('delete-modal-info');
                    var html = '';
                    for (var key in info) {
                        if (info.hasOwnProperty(key) && info[key] !== null && info[key] !== '') {
                            html += '<div class="flex gap-3 py-2">'
                                  + '<span class="font-semibold text-gray-500 w-28 shrink-0">' + key + '</span>'
                                  + '<span class="text-gray-800 break-words">' + info[key] + '</span>'
                                  + '</div>';
                        }
                    }
                    container.innerHTML = html || '<p class="text-gray-400 text-sm py-2">Sem detalhes disponíveis.</p>';

                    var modal = document.getElementById('delete-modal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            document.getElementById('delete-modal-cancel').addEventListener('click', closeModal);

            document.getElementById('delete-modal-confirm').addEventListener('click', function () {
                if (pendingFormId) {
                    document.getElementById(pendingFormId).submit();
                }
            });

            document.getElementById('delete-modal').addEventListener('click', function (e) {
                if (e.target === this) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });
        });
    })();
    </script>
    </body>
</html>
