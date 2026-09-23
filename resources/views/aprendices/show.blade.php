@extends('layouts.app')

@section('titulo', 'Aprendiz · ' . $aprendiz->name)

@section('contenido')
    <div class="space-y-6">
        <a href="{{ route('aprendices.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-300">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Volver a aprendices
        </a>

        {{-- Perfil --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-lg font-bold text-white shadow-sm">
                        {{ strtoupper(substr($aprendiz->name, 0, 1)) }}
                    </span>
                    <div>
                        <h1 class="font-display text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $aprendiz->name }}</h1>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Registrado el {{ $aprendiz->created_at->format('d/m/Y — h:i A') }}</p>
                    </div>
                </div>
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/30">
                    <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                    Aprendiz SENA
                </span>
            </div>

            <dl class="mt-6 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2 lg:grid-cols-4 dark:border-slate-800">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Documento</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $aprendiz->documento ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Correo</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $aprendiz->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Programa</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $aprendiz->programa ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ficha</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $aprendiz->ficha ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Resumen de solicitudes --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-500/30 dark:bg-amber-500/10">
                <i data-lucide="clock" class="h-8 w-8 shrink-0 text-amber-600 dark:text-amber-300"></i>
                <div>
                    <p class="text-2xl font-bold tracking-tight text-amber-700 dark:text-amber-300">{{ $conteos['Pendiente'] }}</p>
                    <p class="text-xs font-medium text-amber-600 dark:text-amber-400/80">Pendientes</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-sky-200 bg-sky-50 p-5 dark:border-sky-500/30 dark:bg-sky-500/10">
                <i data-lucide="loader" class="h-8 w-8 shrink-0 text-sky-600 dark:text-sky-300"></i>
                <div>
                    <p class="text-2xl font-bold tracking-tight text-sky-700 dark:text-sky-300">{{ $conteos['En Proceso'] }}</p>
                    <p class="text-xs font-medium text-sky-600 dark:text-sky-400/80">En proceso</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                <i data-lucide="check-check" class="h-8 w-8 shrink-0 text-emerald-600 dark:text-emerald-300"></i>
                <div>
                    <p class="text-2xl font-bold tracking-tight text-emerald-700 dark:text-emerald-300">{{ $conteos['Resuelto'] }}</p>
                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400/80">Resueltas</p>
                </div>
            </div>
        </div>

        {{-- Solicitudes del aprendiz --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display text-xl font-bold tracking-tight text-slate-900 dark:text-white">Historial de solicitudes</h2>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Solicitudes enviadas por {{ $aprendiz->name }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Registrada</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tipo</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Descripción</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                            @forelse ($solicitudes as $solicitud)
                                @php
                                    $coloresPill = [
                                        'Pendiente' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/30',
                                        'En Proceso' => 'bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-400/30',
                                        'Resuelto' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/30',
                                    ];
                                @endphp
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-500 dark:text-slate-400">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            {{ $solicitud->tipo_requerimiento }}
                                        </span>
                                    </td>
                                    <td class="max-w-[20rem] px-6 py-4">
                                        <p class="truncate text-slate-600 dark:text-slate-300">{{ $solicitud->descripcion }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $coloresPill[$solicitud->estado] }}">
                                            {{ $solicitud->estado }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('solicitudes.show', $solicitud) }}" title="Ver solicitud"
                                           class="inline-flex items-center rounded-lg p-2 text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-slate-500 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16">
                                        <div class="flex flex-col items-center text-center">
                                            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                                <i data-lucide="inbox" class="h-8 w-8"></i>
                                            </span>
                                            <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">Este aprendiz aún no ha enviado solicitudes</p>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Las solicitudes que envie aparecerán aquí.</p>
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
    </div>
@endsection