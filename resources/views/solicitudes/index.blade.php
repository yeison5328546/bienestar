@extends('layouts.app')

@section('titulo', 'Solicitudes')

@section('contenido')
    <div class="space-y-6">

        {{-- Cabecera de página --}}
        <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 md:flex-row md:items-center md:justify-between dark:border-slate-800">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="font-display text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Solicitudes de Bienestar</h1>
                    <span class="rounded-full border border-sena/20 bg-sena/10 px-2.5 py-0.5 text-xs font-semibold text-sena">Gestión Activa {{ date('Y') }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Gestión y seguimiento de apoyos socioeconómicos, orientación psicológica, salud y deporte
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('solicitudes.exportar', array_filter(['estado' => request('estado'), 'alerta' => request('alerta'), 'chat' => request('chat'), 'eliminacion' => request('eliminacion'), 'desde' => request('desde'), 'hasta' => request('hasta'), 'buscar' => request('buscar')])) }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 font-display text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sena/30 active:scale-95 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    <i data-lucide="download" class="h-4 w-4 text-sena"></i>
                    Exportar a Excel
                </a>
                <a href="{{ route('solicitudes.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-sena px-4 py-2.5 font-display text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-sena-dark hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sena focus:ring-offset-2 active:scale-95">
                    <i data-lucide="file-plus" class="h-4 w-4"></i>
                    Nueva solicitud
                </a>
            </div>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="card-lift animate-enter stagger-1 flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm transition duration-200 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start justify-between">
                    <span class="font-label text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Todas las Solicitudes</span>
                    <span class="rounded-lg bg-slate-100 p-2 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <i data-lucide="inbox" class="h-5 w-5"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="font-display text-2xl font-bold text-slate-900 dark:text-white">{{ $conteos['Todos'] }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">solicitudes registradas</div>
                </div>
            </div>

            <div class="card-lift animate-enter stagger-2 flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm transition duration-200 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start justify-between">
                    <span class="font-label text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Pendientes</span>
                    <span class="rounded-lg bg-amber-50 p-2 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                        <i data-lucide="pending" class="h-5 w-5"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="font-display text-2xl font-bold text-slate-900 dark:text-white">{{ $conteos['Pendiente'] }}</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-300">Por revisar</span>
                    </div>
                </div>
            </div>

            <div class="card-lift animate-enter stagger-3 flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm transition duration-200 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start justify-between">
                    <span class="font-label text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">En Proceso</span>
                    <span class="rounded-lg bg-blue-50 p-2 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                        <i data-lucide="sync" class="h-5 w-5"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="font-display text-2xl font-bold text-slate-900 dark:text-white">{{ $conteos['En Proceso'] }}</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded border border-sky-200 bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-800 dark:border-sky-400/30 dark:bg-sky-500/10 dark:text-sky-300">En atención y comités</span>
                    </div>
                </div>
            </div>

            <div class="card-lift animate-enter stagger-4 flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm transition duration-200 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start justify-between">
                    <span class="font-label text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Resueltas</span>
                    <span class="rounded-lg bg-emerald-50 p-2 text-sena dark:bg-emerald-500/10 dark:text-emerald-300">
                        <i data-lucide="check-circle" class="h-5 w-5"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="font-display text-2xl font-bold text-slate-900 dark:text-white">{{ $conteos['Resuelto'] }}</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:border-emerald-400/30 dark:bg-emerald-500/10 dark:text-emerald-300">Completadas</span>
                    </div>
                </div>
            </div>

            <div class="card-lift animate-enter stagger-5 relative flex flex-col justify-between overflow-hidden rounded-xl border-x border-b border-amber-200 border-t-4 border-t-lemon bg-gradient-to-b from-amber-50/60 to-white p-4 shadow-sm transition duration-200 hover:shadow-md dark:border-amber-400/20 dark:border-t-lemon/60 dark:from-slate-900/60 dark:to-slate-900/60">
                <div class="flex items-start justify-between">
                    <span class="font-label flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-amber-900 dark:text-amber-300">
                        <i data-lucide="alert-triangle" class="h-3.5 w-3.5 text-lemon"></i>
                        Prioritarias
                    </span>
                    <span class="rounded-lg bg-lemon/20 p-2 text-amber-700 dark:text-yellow-300">
                        <i data-lucide="priority-high" class="h-5 w-5"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="font-display text-2xl font-black text-rose-600 dark:text-rose-400">{{ $conteos['Prioritarias'] }}</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center gap-1 rounded border border-rose-300 bg-red-100/80 px-2 py-0.5 text-xs font-semibold text-red-700 dark:border-rose-400/30 dark:bg-rose-500/10 dark:text-rose-300">
                            <span class="h-1.5 w-1.5 animate-ping rounded-full bg-rose-600"></span>
                            Atención urgente
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerta de chats pendientes de atención en vivo --}}
        @if ($chatsPendientes > 0)
            <div class="flex flex-col gap-3 rounded-xl border border-sky-200 bg-sky-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-sky-500/30 dark:bg-sky-500/10">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-300">
                        <i data-lucide="message-circle" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-sky-900 dark:text-sky-200">
                            {{ $chatsPendientes }} {{ $chatsPendientes === 1 ? 'aprendiz solicita' : 'aprendices solicitan' }} atención en vivo por chat
                        </p>
                        <p class="mt-0.5 text-sm text-sky-700/80 dark:text-sky-300/80">Estos aprendices están esperando confirmación para hablar contigo sobre su solicitud.</p>
                    </div>
                </div>
                <a href="{{ route('solicitudes.index', array_filter(['chat' => 'pendiente', 'buscar' => request('buscar')])) }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-500/30 active:scale-95">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                    Ver chats pendientes
                </a>
            </div>
        @endif

        {{-- Alerta de casos prioritarios detectados por la IA --}}
        @if ($conteos['Prioritarias'] > 0)
            <div class="flex flex-col gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-rose-500/30 dark:bg-rose-500/10">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300">
                        <i data-lucide="bell-ring" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-rose-900 dark:text-rose-200">
                            {{ $conteos['Prioritarias'] }} {{ $conteos['Prioritarias'] === 1 ? 'caso requiere' : 'casos requieren' }} atención prioritaria
                        </p>
                        <p class="mt-0.5 text-sm text-rose-700/80 dark:text-rose-300/80">La IA marcó estas solicitudes como delicadas y fueron notificadas a Natalia para atenderlas primero.</p>
                    </div>
                </div>
                <a href="{{ route('solicitudes.index', array_filter(['alerta' => 1, 'buscar' => request('buscar')])) }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-500/30 active:scale-95">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                    Ver prioritarias
                </a>
            </div>
        @endif

        {{-- Alerta de peticiones de eliminación de aprendices --}}
        @if ($conteos['Eliminaciones'] > 0)
            <div class="flex flex-col gap-3 rounded-xl border border-violet-200 bg-violet-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-violet-500/30 dark:bg-violet-500/10">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-300">
                        <i data-lucide="user-round-x" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-violet-900 dark:text-violet-200">
                            {{ $conteos['Eliminaciones'] }} {{ $conteos['Eliminaciones'] === 1 ? 'aprendiz solicita' : 'aprendices solicitan' }} eliminar su solicitud
                        </p>
                        <p class="mt-0.5 text-sm text-violet-700/80 dark:text-violet-300/80">Cada petición incluye la explicación del aprendiz. Debes revisarla y decidir si la eliminas o la mantienes.</p>
                    </div>
                </div>
                <a href="{{ route('solicitudes.index', array_filter(['eliminacion' => 'pendiente', 'buscar' => request('buscar')])) }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700 focus:outline-none focus:ring-4 focus:ring-violet-500/30 active:scale-95">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                    Revisar peticiones
                </a>
            </div>
        @endif

        {{-- Barra de filtros --}}
        <div class="card-lift space-y-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <form method="GET" action="{{ route('solicitudes.index') }}" role="search">
                @if (request('estado'))
                    <input type="hidden" name="estado" value="{{ request('estado') }}">
                @endif
                @if (request('alerta'))
                    <input type="hidden" name="alerta" value="{{ request('alerta') }}">
                @endif
                @if (request('chat'))
                    <input type="hidden" name="chat" value="{{ request('chat') }}">
                @endif
                @if (request('eliminacion') === 'pendiente')
                    <input type="hidden" name="eliminacion" value="pendiente">
                @endif

                {{-- Fila 1: buscador + fechas --}}
                <div class="grid grid-cols-1 gap-4 pb-4 lg:grid-cols-3">
                    <div class="relative lg:col-span-1">
                        <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="filtro-buscar" type="search" name="buscar" value="{{ request('buscar') }}"
                               class="w-full rounded-lg border border-slate-300 bg-white py-1.5 pl-9 pr-3 text-xs text-slate-700 placeholder-slate-400 transition focus:border-sena focus:outline-none focus:ring-2 focus:ring-sena/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                               placeholder="Buscar por nombre o documento...">
                    </div>
                    <div class="relative">
                        <input id="filtro-desde" type="hidden" name="desde" value="{{ request('desde') }}">
                        <input id="filtro-hasta" type="hidden" name="hasta" value="{{ request('hasta') }}">
                        <i data-lucide="calendar-days"
                           class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-sena"></i>
                        <input id="filtro-fechas" type="text" readonly autocomplete="off"
                               class="w-full cursor-pointer rounded-lg border border-slate-300 bg-white py-1.5 pl-9 pr-3 text-xs text-slate-700 placeholder-slate-400 transition focus:border-sena focus:outline-none focus:ring-2 focus:ring-sena/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                               placeholder="dd/mm/aaaa a dd/mm/aaaa">
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-sena px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-sena-dark active:scale-95">
                            <i data-lucide="filter" class="h-4 w-4"></i>
                            Filtrar
                        </button>
                        @if (request()->hasAny(['desde', 'hasta', 'chat', 'eliminacion', 'buscar', 'alerta', 'estado']))
                            <a href="{{ route('solicitudes.index') }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Fila 2: chips de estado / canal --}}
                <div class="flex flex-wrap items-center gap-1.5 border-t border-slate-100 pt-4 dark:border-slate-800">
                    @php
                        $pestanas = [
                            'Todos' => 'Todos',
                            'Pendiente' => 'Pendiente',
                            'En Proceso' => 'En Proceso',
                            'Resuelto' => 'Resuelto',
                        ];
                        $activo = request()->has('alerta') ? 'Prioritarias' : (request('eliminacion') === 'pendiente' ? 'Eliminar' : (request('estado') ?? 'Todos'));
                    @endphp
                    @if ($conteos['Prioritarias'] > 0)
                        <a href="{{ route('solicitudes.index', array_filter(['alerta' => 1, 'buscar' => request('buscar')])) }}"
                           class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition active:scale-95 {{ $activo === 'Prioritarias' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-400/30' }}">
                            <i data-lucide="bell-ring" class="h-3.5 w-3.5"></i>
                            Prioritarias
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold {{ $activo === 'Prioritarias' ? 'bg-white/15' : 'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' }}">{{ $conteos['Prioritarias'] }}</span>
                        </a>
                    @endif
@if ($conteos['Eliminaciones'] > 0)
                        <a href="{{ route('solicitudes.index', array_filter(['eliminacion' => request('eliminacion') === 'pendiente' ? null : 'pendiente', 'buscar' => request('buscar')])) }}"
                           class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition active:scale-95 {{ request('eliminacion') === 'pendiente' ? 'bg-violet-600 text-white shadow-sm' : 'bg-violet-50 text-violet-700 ring-1 ring-inset ring-violet-200 hover:bg-violet-100 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-400/30' }}">
                            <i data-lucide="user-round-x" class="h-3.5 w-3.5"></i>
                            Piden eliminar
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold {{ request('eliminacion') === 'pendiente' ? 'bg-white/15' : 'bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-300' }}">{{ $conteos['Eliminaciones'] }}</span>
                        </a>
                    @endif
                    @if ($chatsPendientes > 0)
                        <a href="{{ route('solicitudes.index', array_filter(['chat' => 'pendiente', 'buscar' => request('buscar')])) }}"
                           class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition active:scale-95 {{ request('chat') === 'pendiente' ? 'bg-sky-600 text-white shadow-sm' : 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200 hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-400/30' }}">
                            <i data-lucide="message-circle" class="h-3.5 w-3.5"></i>
                            Chats pendientes
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold {{ request('chat') === 'pendiente' ? 'bg-white/15' : 'bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-300' }}">{{ $chatsPendientes }}</span>
                        </a>
                    @endif
                    @foreach ($pestanas as $valor => $etiqueta)
                        <a href="{{ route('solicitudes.index', array_filter(['estado' => $valor === 'Todos' ? null : $valor, 'buscar' => request('buscar')])) }}"
                           class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition active:scale-95 {{ $activo === $valor ? 'bg-sena text-white shadow-sm' : 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' }}">
                            {{ $etiqueta }}
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold {{ $activo === $valor ? 'bg-white/15' : 'bg-white/60 text-slate-500 dark:bg-slate-700 dark:text-slate-300' }}">{{ $conteos[$valor] }}</span>
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Tabla de solicitudes --}}
        <div class="card-lift overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-100/70 font-label text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-400">
                            <th scope="col" class="px-6 py-3.5">Aprendiz</th>
                            <th scope="col" class="px-4 py-3.5">Tipo de Solicitud</th>
                            <th scope="col" class="px-4 py-3.5 text-center">Prioridad</th>
                            <th scope="col" class="px-4 py-3.5 text-center">Estado</th>
                            <th scope="col" class="px-4 py-3.5 text-center">Chat</th>
                            <th scope="col" class="px-4 py-3.5">Fecha de Registro</th>
                            <th scope="col" class="px-4 py-3.5">Descripción</th>
                            <th scope="col" class="px-4 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @forelse ($solicitudes as $solicitud)
                            @php
                                $chatActual = $solicitud->conversaciones->sortByDesc('id')->first();
                                $coloresPill = [
                                    'Pendiente' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/30',
                                    'En Proceso' => 'bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-400/30',
                                    'Resuelto' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/30',
                                ];
                                $coloresChat = [
                                    'solicitada' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/30',
                                    'activa' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/30',
                                    'cerrada' => 'bg-slate-100 text-slate-500 ring-slate-600/20 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-400/30',
                                ];
                                $textosChat = [
                                    'solicitada' => 'Pendiente',
                                    'activa' => 'En curso',
                                    'cerrada' => 'Cerrado',
                                ];
                            @endphp
                            <tr class="transition-colors duration-150 group {{ $solicitud->esPrioritaria() ? 'bg-rose-50/40 hover:bg-rose-50/70 dark:bg-rose-500/[0.06] dark:hover:bg-rose-500/10' : ($chatActual && $chatActual->estado === 'solicitada' ? 'bg-sky-50/40 hover:bg-sky-50/70 dark:bg-sky-500/[0.06] dark:hover:bg-sky-500/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40') }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sena/10 text-xs font-bold text-sena ring-1 ring-sena/30 dark:bg-sena/20 dark:text-emerald-300 dark:ring-sena/40">
                                            {{ strtoupper(substr($solicitud->nombre_aprendiz, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="font-display text-sm font-semibold text-slate-900 transition-colors hover:text-sena dark:text-white dark:hover:text-emerald-300">
                                                {{ $solicitud->nombre_aprendiz }}
                                            </a>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">Doc. {{ $solicitud->documento }} · {{ $solicitud->ficha ?? 'Sin ficha' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $solicitud->tipo_requerimiento }}
                                    </span>
                                    <span class="mt-1 block max-w-[10rem] truncate text-[11px] text-slate-400 dark:text-slate-500">{{ $solicitud->etiqueta ?? 'Sin etiquetar' }}</span>
                                        @if ($solicitud->peticionEliminacionPendiente())
                                            <span class="mt-1.5 inline-flex w-fit items-center gap-1 rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-semibold text-violet-700 ring-1 ring-inset ring-violet-300 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-400/30">
                                                <i data-lucide="clock" class="h-3 w-3"></i>
                                                Solicita eliminación
                                            </span>
                                        @elseif ($solicitud->peticionEliminacionRechazada())
                                            <span class="mt-1.5 inline-flex w-fit items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500 ring-1 ring-inset ring-slate-300 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-600/40">
                                                <i data-lucide="ban" class="h-3 w-3"></i>
                                                Eliminación rechazada
                                            </span>
                                        @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <x-prioridad :solicitud="$solicitud" />
                                        @if ($solicitud->esPrioritaria())
                                            <i data-lucide="bell-ring" class="h-4 w-4 shrink-0 text-rose-500" title="Requiere atención prioritaria"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <form method="POST" action="{{ route('solicitudes.estado', $solicitud) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <span class="relative inline-flex items-center">
                                            <select name="estado" onchange="this.form.submit()" aria-label="Cambiar estado"
                                                    class="cursor-pointer appearance-none rounded-full py-1.5 pl-3 pr-8 text-xs font-semibold ring-1 ring-inset transition focus:outline-none focus:ring-2 {{ $coloresPill[$solicitud->estado] }}">
                                                @foreach ($estados as $estado)
                                                    <option value="{{ $estado }}" @selected($solicitud->estado === $estado)>{{ $estado }}</option>
                                                @endforeach
                                            </select>
                                            <i data-lucide="chevron-down" class="pointer-events-none absolute right-2.5 h-3.5 w-3.5 opacity-70"></i>
                                        </span>
                                    </form>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if ($chatActual)
                                        <a href="{{ route('solicitudes.show', $solicitud) }}"
                                           class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 ring-inset transition hover:shadow-sm {{ $coloresChat[$chatActual->estado] ?? 'bg-slate-100 text-slate-500 ring-slate-600/20 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-400/30' }}">
                                            @if ($chatActual->estado === 'solicitada')
                                                <span class="h-1.5 w-1.5 shrink-0 animate-pulse rounded-full bg-amber-500"></span>
                                            @elseif ($chatActual->estado === 'activa')
                                                <span class="h-1.5 w-1.5 shrink-0 animate-pulse rounded-full bg-sena"></span>
                                            @endif
                                            {{ $textosChat[$chatActual->estado] ?? '' }}
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                <td class="max-w-[14rem] px-4 py-4">
                                    <p class="truncate text-slate-600 dark:text-slate-300">{{ $solicitud->descripcion }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('solicitudes.show', $solicitud) }}" title="Ver detalle"
                                           class="inline-flex items-center gap-1 rounded-md px-2.5 py-1 text-xs font-medium text-sena transition hover:bg-sena/10 active:scale-95 dark:hover:bg-sena/20">
                                            <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('solicitudes.edit', $solicitud) }}" title="Editar"
                                           class="rounded-md p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-20">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                            <i data-lucide="inbox" class="h-8 w-8"></i>
                                        </span>
                                        <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No hay solicitudes registradas</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cuando registres una solicitud, aparecerá aquí.</p>
                                        <a href="{{ route('solicitudes.create') }}"
                                           class="mt-5 inline-flex items-center gap-2 rounded-xl bg-sena px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sena-dark">
                                            <i data-lucide="file-plus" class="h-4 w-4"></i>
                                            Registrar la primera solicitud
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($solicitudes->hasPages())
                <div class="flex justify-end border-t border-slate-100 bg-white px-6 py-4">
                    {{ $solicitudes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection