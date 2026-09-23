@extends('layouts.aprendiz')

@section('titulo', 'Mis solicitudes')

@section('contenido')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Hola, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Estas son tus solicitudes y su estado actual de atención.</p>
            </div>
            <a href="{{ route('portal.solicitudes.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                <i data-lucide="file-plus" class="h-4 w-4"></i>
                Reportar una solicitud
            </a>
        </div>

        <div class="card-lift rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-white">
                    <i data-lucide="clipboard-list" class="h-4 w-4 text-indigo-600 dark:text-indigo-400"></i>
                    Historial de mis solicitudes
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                    <thead>
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Fecha</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tipo</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Descripción</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Estado</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Edición</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @forelse ($solicitudes as $solicitud)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                <td class="whitespace-nowrap px-5 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $solicitud->created_at->format('d/m/Y') }}
                                    <span class="block text-xs">{{ $solicitud->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $solicitud->tipo_requerimiento }}
                                    </span>
                                    @if ($solicitud->analizado)
                                        <div class="mt-1.5"><x-prioridad :solicitud="$solicitud" /></div>
                                    @endif
                                    @if ($solicitud->peticionEliminacionPendiente())
                                        <span class="mt-1.5 inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-semibold text-violet-700 ring-1 ring-inset ring-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-400/30">
                                            <i data-lucide="clock" class="h-3 w-3"></i>
                                            En espera de eliminación
                                        </span>
                                    @elseif ($solicitud->peticionEliminacionRechazada())
                                        <span class="mt-1.5 inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500 ring-1 ring-inset ring-slate-300 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-600/40">
                                            <i data-lucide="ban" class="h-3 w-3"></i>
                                            Sin eliminar (Bienestar)
                                        </span>
                                    @endif
                                </td>
                                <td class="max-w-[18rem] px-5 py-4">
                                    <p class="truncate text-slate-600 dark:text-slate-300">{{ $solicitud->descripcion }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <x-estado :solicitud="$solicitud" />
                                </td>
                                <td class="px-5 py-4">
                                    @if ($solicitud->edicionDisponibleParaAprendiz())
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/30">
                                            <i data-lucide="timer" class="h-3.5 w-3.5"></i>
                                            Edición disponible por {{ $solicitud->textoVentanaEdicion() }} más
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                                            <i data-lucide="lock" class="h-3.5 w-3.5"></i>
                                            Edición cerrada
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <a href="{{ route('portal.solicitudes.show', $solicitud) }}" title="Ver detalle"
                                           class="inline-flex items-center rounded-lg p-2 text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-slate-500 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        @if ($solicitud->edicionDisponibleParaAprendiz())
                                            <a href="{{ route('portal.solicitudes.edit', $solicitud) }}" title="Editar"
                                               class="inline-flex items-center rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:text-slate-500 dark:hover:bg-slate-700 dark:hover:text-slate-200">
                                                <i data-lucide="pencil" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-20">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                            <i data-lucide="package-open" class="h-8 w-8"></i>
                                        </span>
                                        <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">Aún no has enviado solicitudes</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Reporta tu primer requerimiento desde tu portal.</p>
                                        <a href="{{ route('portal.solicitudes.create') }}"
                                           class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                            <i data-lucide="file-plus" class="h-4 w-4"></i>
                                            Reportar solicitud
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($solicitudes->hasPages())
            <div class="flex justify-end">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>
@endsection