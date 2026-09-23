<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Gestión de solicitudes') · Bienestar al Aprendiz</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-[#F8FAFC] font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-300">

    {{-- Barra de navegación superior --}}
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <span class="pointer-events-none absolute inset-x-0 top-0 z-10 h-[3px] bg-gradient-to-r from-sena via-lemon to-sena-deep"></span>

        <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-3 px-4 py-2.5 sm:px-6 lg:px-8">
            {{-- Marca --}}
            <a href="{{ route('solicitudes.index') }}" class="flex shrink-0 items-center gap-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sena text-white shadow-sm ring-2 ring-sena/20">
                    <i data-lucide="school" class="h-5 w-5"></i>
                </span>
                <span class="flex min-w-0 flex-col">
                    <span class="whitespace-nowrap font-display text-base font-bold leading-tight text-sena">
                        SENA <span class="font-normal text-slate-400">|</span> <span class="text-sm font-semibold text-slate-800 dark:text-white">Bienestar al Aprendiz</span>
                    </span>
                    <span class="hidden truncate text-[11px] font-medium text-slate-500 xl:block dark:text-slate-400">Regional Distrito Capital · Sede Central</span>
                </span>
            </a>

            {{-- Navegación escritorio (flexible, todos los enlaces siempre visibles) --}}
            <nav class="hidden flex-1 items-center justify-between gap-2 whitespace-nowrap px-3 font-display text-[13px] xl:flex">
                <a href="{{ route('aprendices.index') }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 {{ request()->routeIs('aprendices.*') ? 'border-b-2 border-sena font-bold text-sena dark:text-sena' : '' }}">
                    <i data-lucide="group" class="h-4 w-4"></i>
                    Aprendices
                </a>
                <a href="{{ route('solicitudes.index') }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 {{ request()->routeIs('solicitudes.*') && !request()->routeIs('solicitudes.create') ? 'border-b-2 border-sena font-bold text-sena dark:text-sena' : '' }}">
                    <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                    Solicitudes
                </a>
                <a href="{{ route('solicitudes.create') }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 {{ request()->routeIs('solicitudes.create') ? 'border-b-2 border-sena font-bold text-sena dark:text-sena' : '' }}">
                    <i data-lucide="file-plus" class="h-4 w-4"></i>
                    Nueva solicitud
                </a>
                <a href="{{ route('solicitudes.index', ['estado' => 'Pendiente']) }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100">
                    <i data-lucide="clock" class="h-4 w-4 text-amber-500"></i>
                    Pendientes
                </a>
                <a href="{{ route('solicitudes.index', array_filter(['eliminacion' => 'pendiente'])) }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100">
                    <i data-lucide="user-round-x" class="h-4 w-4 text-violet-500"></i>
                    Peticiones
                    @if (($eliminacionesPendientes ?? 0) > 0)
                        <span class="rounded-full bg-violet-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $eliminacionesPendientes }}</span>
                    @endif
                </a>
                <a href="{{ route('solicitudes.index', array_filter(['chat' => 'pendiente'])) }}"
                   class="flex shrink-0 items-center gap-1.5 pb-1 font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100">
                    <i data-lucide="message-circle" class="h-4 w-4 text-sky-500"></i>
                    Chats
                    @if (($chatPendientes ?? 0) > 0)
                        <span class="rounded-full bg-sky-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $chatPendientes }}</span>
                    @endif
                </a>
            </nav>

            {{-- Acciones derecha (fijas, nunca se encogen) --}}
            <div class="flex shrink-0 items-center gap-2 sm:gap-2.5 lg:gap-3">
                {{-- Indicador de estatus --}}
                <span class="hidden shrink-0 items-center gap-2 whitespace-nowrap rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 2xl:inline-flex dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <span class="h-2 w-2 shrink-0 animate-pulse rounded-full bg-sena dark:bg-emerald-400"></span>
                    Regional Distrito Capital
                </span>

                {{-- Chats pendientes --}}
                <a href="{{ route('solicitudes.index', array_filter(['chat' => 'pendiente'])) }}"
                   title="{{ ($chatPendientes ?? 0) > 0 ? "$chatPendientes chats pendientes por atender" : 'Sin chats pendientes' }}"
                   class="relative inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <i data-lucide="message-circle" class="h-5 w-5"></i>
                    @if (($chatPendientes ?? 0) > 0)
                        <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-sena"></span>
                    @endif
                </a>

                {{-- Peticiones de eliminación pendientes --}}
                <a href="{{ route('solicitudes.index', array_filter(['eliminacion' => 'pendiente'])) }}"
                   title="{{ ($eliminacionesPendientes ?? 0) > 0 ? "$eliminacionesPendientes aprendices solicitan eliminar su solicitud" : 'Sin peticiones de eliminación' }}"
                   class="relative inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <i data-lucide="user-round-x" class="h-5 w-5"></i>
                    @if (($eliminacionesPendientes ?? 0) > 0)
                        <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-violet-500"></span>
                    @endif
                </a>

                <button type="button" id="tema-toggle" aria-label="Cambiar tema"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <i data-lucide="moon" class="block h-5 w-5 dark:hidden"></i>
                    <i data-lucide="sun" class="hidden h-5 w-5 dark:block"></i>
                </button>

                <span class="hidden h-6 w-px bg-slate-200 sm:block dark:bg-slate-700"></span>

                {{-- Perfil --}}
                <div class="flex items-center gap-3 pl-1">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sena/15 text-sm font-semibold text-sena ring-2 ring-sena/30 dark:bg-sena/20 dark:text-emerald-300">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden sm:block">
                        <span class="block max-w-32 truncate text-xs font-bold leading-tight text-slate-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-[11px] text-slate-500 dark:text-slate-400">Administradora de Bienestar</span>
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" aria-label="Cerrar sesión" title="Cerrar sesión"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Navegación móvil / tablet (envuelve en varias líneas, sin scroll) --}}
        <nav class="flex flex-wrap items-center gap-1.5 border-t border-slate-100 px-4 py-2 sm:px-6 lg:px-8 xl:hidden dark:border-slate-800/60">
            <a href="{{ route('aprendices.index') }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('aprendices.*') ? 'bg-sena text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <i data-lucide="group" class="h-3.5 w-3.5"></i>
                Aprendices
            </a>
            <a href="{{ route('solicitudes.index') }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('solicitudes.*') && !request()->routeIs('solicitudes.create') ? 'bg-sena text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i>
                Solicitudes
            </a>
            <a href="{{ route('solicitudes.create') }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('solicitudes.create') ? 'bg-sena text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <i data-lucide="file-plus" class="h-3.5 w-3.5"></i>
                Nueva solicitud
            </a>
            <a href="{{ route('solicitudes.index', ['estado' => 'Pendiente']) }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('solicitudes.*') ? 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' : '' }}">
                <i data-lucide="clock" class="h-3.5 w-3.5 text-amber-500"></i>
                Pendientes
            </a>
            <a href="{{ route('solicitudes.index', array_filter(['eliminacion' => 'pendiente'])) }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('solicitudes.*') ? 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' : '' }}">
                <i data-lucide="user-round-x" class="h-3.5 w-3.5 text-violet-500"></i>
                Peticiones
                @if (($eliminacionesPendientes ?? 0) > 0)
                    <span class="rounded-full bg-violet-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $eliminacionesPendientes }}</span>
                @endif
            </a>
            <a href="{{ route('solicitudes.index', array_filter(['chat' => 'pendiente'])) }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition {{ request()->routeIs('solicitudes.*') ? 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' : '' }}">
                <i data-lucide="message-circle" class="h-3.5 w-3.5 text-sky-500"></i>
                Chats
                @if (($chatPendientes ?? 0) > 0)
                    <span class="rounded-full bg-sky-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $chatPendientes }}</span>
                @endif
            </a>
        </nav>
    </header>

    {{-- Contenido --}}
    <div class="flex-1">
        <main class="mx-auto w-full max-w-7xl animate-fade-in px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
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
    </div>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
