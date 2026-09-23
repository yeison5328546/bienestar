@extends('layouts.app')

@section('titulo', 'Aprendices')

@section('contenido')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Aprendices</h1>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Aprendices registrados en el sistema</p>
            </div>
            <a href="{{ route('aprendices.create') }}"
               class="inline-flex items-center gap-2 self-start rounded-xl bg-lemon px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-lemon-deep active:scale-[.98] focus:outline-none focus:ring-4 focus:ring-lemon/40">
                <i data-lucide="user-plus" class="h-4 w-4"></i>
                Registrar aprendiz
            </a>
        </div>

        {{-- Resumen --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="card-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <i data-lucide="users" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $conteos['total'] }}</p>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Aprendices registrados</p>
                    </div>
                </div>
            </div>
            <div class="card-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <i data-lucide="send" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $conteos['conSolicitudes'] }}</p>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Con solicitudes</p>
                    </div>
                </div>
            </div>
            <div class="card-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        <i data-lucide="user-x" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $conteos['sinSolicitudes'] }}</p>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Sin solicitudes</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buscador --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 dark:border-slate-800 dark:bg-slate-900/60">
            <form method="GET" action="{{ route('aprendices.index') }}" role="search">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="filtro-buscar" type="search" name="buscar" value="{{ request('buscar') }}"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-10 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:bg-slate-950"
                           placeholder="Buscar por nombre, documento, correo, programa o ficha...">
                    @if (request('buscar'))
                        <a href="{{ route('aprendices.index') }}"
                           class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                           title="Limpiar búsqueda">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white card-lift shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Aprendiz</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Contacto</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Programa / Ficha</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Solicitudes</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Registrado</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @forelse ($aprendices as $aprendiz)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                                            {{ strtoupper(substr($aprendiz->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('aprendices.show', $aprendiz) }}" class="font-semibold text-slate-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-300">
                                                {{ $aprendiz->name }}
                                            </a>
                                            <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Doc. {{ $aprendiz->documento ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $aprendiz->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="max-w-[14rem] truncate text-slate-600 dark:text-slate-300">{{ $aprendiz->programa ?? '—' }}</div>
                                    <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Ficha: {{ $aprendiz->ficha ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($aprendiz->solicitudes_count > 0)
                                        <a href="{{ route('aprendices.show', $aprendiz) }}"
                                           class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20 transition hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/30 dark:hover:bg-indigo-500/20">
                                            {{ $aprendiz->solicitudes_count }} @choice('solicitud|solicitudes', $aprendiz->solicitudes_count)
                                        </a>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400">Sin solicitudes</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500 dark:text-slate-400">{{ $aprendiz->created_at->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <a href="{{ route('aprendices.show', $aprendiz) }}" title="Ver detalle"
                                       class="inline-flex items-center rounded-lg p-2 text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-slate-500 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                            <i data-lucide="users" class="h-8 w-8"></i>
                                        </span>
                                        <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No hay aprendices registrados</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pulsa "Registrar aprendiz" para crear la cuenta de un aprendiz.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($aprendices->hasPages())
            <div class="flex justify-end">
                {{ $aprendices->links() }}
            </div>
        @endif
    </div>
@endsection