<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Portal del Aprendiz') · Bienestar al Aprendiz</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased dark:bg-slate-950 dark:text-slate-300">

    <header class="relative sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
        <span class="pointer-events-none absolute inset-x-0 top-0 z-10 h-[3px] bg-gradient-to-r from-sena via-lemon to-sena-deep"></span>
        <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('portal.index') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-sm">
                    <i data-lucide="heart-pulse" class="h-5 w-5"></i>
                </span>
                <span>
                    <span class="block font-display text-sm font-bold tracking-tight text-slate-900 dark:text-white">Bienestar al Aprendiz</span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400">Portal del aprendiz</span>
                </span>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('portal.solicitudes.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    <span class="hidden sm:inline">Nueva solicitud</span>
                </a>

                {{-- Campana de notificaciones (mensajes/citas de Bienestar) --}}
                @php
                    $unreadCount = Auth::user()->unreadNotifications()->count();
                    $ultimasNotif = Auth::user()->notifications()->limit(8)->get();
                @endphp
                <div class="relative">
                    <button type="button" id="bell-btn" aria-label="Notificaciones" title="Notificaciones"
                            class="relative inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">
                        <i data-lucide="bell" class="h-5 w-5"></i>
                        <span id="notif-badge"
                              class="{{ $unreadCount > 0 ? '' : 'hidden' }} absolute -right-1.5 -top-1.5 inline-flex min-w-[18px] items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white shadow-sm">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    </button>

                    <div id="bell-dropdown"
                         class="absolute right-0 z-40 mt-2 hidden w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Notificaciones</p>
                            <button type="button" id="notif-marcar"
                                    class="text-xs font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">Marcar todas como leídas</button>
                        </div>

                        @forelse ($ultimasNotif as $notif)
                            <a href="{{ route('portal.solicitudes.show', $notif->data['solicitud_id'] ?? 0) }}"
                               class="block border-b border-slate-50 px-4 py-3 transition hover:bg-slate-50 dark:border-slate-700/60 dark:hover:bg-slate-700/40">
                                <span class="flex items-center gap-2 text-sm font-semibold {{ $notif->read_at ? 'text-slate-500 dark:text-slate-400' : 'text-slate-900 dark:text-white' }}">
                                    @if (! $notif->read_at)
                                        <span class="h-2 w-2 shrink-0 rounded-full bg-indigo-500"></span>
                                    @endif
                                    {{ $notif->data['titulo'] ?? 'Nuevo mensaje de Bienestar' }}
                                </span>
                                <span class="mt-0.5 block truncate text-xs text-slate-500 dark:text-slate-400">{{ $notif->data['contenido'] ?? '' }}</span>
                                <span class="mt-1 block text-[11px] text-slate-400">{{ $notif->created_at?->diffForHumans() }}</span>
                            </a>
                        @empty
                            <p class="px-4 py-8 text-center text-sm text-slate-400 dark:text-slate-500">Sin notificaciones por ahora.</p>
                        @endforelse
                    </div>
                </div>

                <button type="button" id="tema-toggle" aria-label="Cambiar tema"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">
                    <i data-lucide="moon" class="block h-5 w-5 dark:hidden"></i>
                    <i data-lucide="sun" class="hidden h-5 w-5 dark:block"></i>
                </button>

                <span class="hidden h-8 w-px bg-slate-200 sm:block dark:bg-slate-700"></span>

                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden sm:block">
                        <span class="block text-sm font-semibold leading-5 text-slate-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-xs leading-4 text-slate-500 dark:text-slate-400">Aprendiz</span>
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" aria-label="Cerrar sesión" title="Cerrar sesión"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-rose-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </div>

        <nav class="flex items-center gap-1 overflow-x-auto border-t border-slate-100 px-4 py-2 sm:px-6 lg:px-8 dark:border-slate-800/60">
            <a href="{{ route('portal.index') }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ request()->routeIs('portal.index') ? 'bg-slate-900 text-white dark:bg-slate-800 dark:text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                Mis solicitudes
            </a>
            <a href="{{ route('portal.solicitudes.create') }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ request()->routeIs('portal.solicitudes.create') ? 'bg-slate-900 text-white dark:bg-slate-800 dark:text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <i data-lucide="file-plus" class="h-4 w-4"></i>
                Reportar solicitud
            </a>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl animate-fade-in px-4 py-6 sm:px-6 lg:py-8">
        @if (session('exito'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300">
                    <i data-lucide="check" class="h-4 w-4"></i>
                </span>
                {{ session('exito') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300">
                    <i data-lucide="alert-circle" class="h-4 w-4"></i>
                </span>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300">
                    <i data-lucide="alert-circle" class="h-4 w-4"></i>
                </span>
                <span class="flex-1">
                    <span class="font-semibold">No se pudo completar la acción.</span>
                    <ul class="mt-1 list-disc space-y-0.5 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </span>
            </div>
        @endif

        @yield('contenido')
    </main>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    {{-- Campana de notificaciones: actualiza el contador sin recargar la página --}}
    <script>
        (function () {
            var badge = document.getElementById('notif-badge');
            var btn = document.getElementById('bell-btn');
            var drop = document.getElementById('bell-dropdown');
            var csrf = document.querySelector('meta[name="csrf-token"]');

            function setBadge(n) {
                if (!badge) return;
                if (n > 0) {
                    badge.textContent = n > 99 ? '99+' : n;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            function marcar() {
                fetch('{{ route('portal.notificaciones.marcar-leidas') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf ? csrf.content : '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams()
                })
                    .then(function (r) { return r.json(); })
                    .then(function (d) { if (d.ok) setBadge(0); })
                    .catch(function () {});
            }

            if (btn && drop) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    drop.classList.toggle('hidden');
                });
                document.addEventListener('click', function (e) {
                    if (!drop.classList.contains('hidden') && !drop.contains(e.target)) {
                        drop.classList.add('hidden');
                    }
                });
                drop.querySelectorAll('a').forEach(function (a) {
                    a.addEventListener('click', function () { drop.classList.add('hidden'); });
                });
            }

            var marcarBtn = document.getElementById('notif-marcar');
            if (marcarBtn) {
                marcarBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    marcar();
                });
            }

            window.setInterval(function () {
                fetch('{{ route('portal.notificaciones.no-leidas') }}', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (d) {
                        if (typeof d.no_leidas !== 'undefined') setBadge(d.no_leidas);
                    })
                    .catch(function () {});
            }, 20000);
        })();
    </script>
</body>
</html>