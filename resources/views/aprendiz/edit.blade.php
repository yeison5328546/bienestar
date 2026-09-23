@extends('layouts.aprendiz')

@section('titulo', 'Corregir solicitud')

@section('contenido')
    <div class="mx-auto flex max-w-2xl flex-col gap-6">
        <div>
            <a href="{{ route('portal.solicitudes.show', $solicitud) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Volver al detalle
            </a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Corregir solicitud</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Puedes ajustar los detalles de tu reporte.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                <i data-lucide="timer" class="mt-0.5 h-5 w-5 shrink-0"></i>
                <div>
                    <p class="font-semibold">Edición disponible por {{ $solicitud->textoVentanaEdicion() }} más</p>
                    <p class="mt-0.5 text-xs opacity-90">La solicitaste el {{ $solicitud->created_at->format('d/m/Y \a \l\a\s H:i') }}. Pasados los 15 minutos, quedará bloqueada para ti.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('portal.solicitudes.update', $solicitud) }}">
                @csrf
                @method('PUT')
                @include('aprendiz._form', ['solicitud' => $solicitud])

                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end dark:border-slate-800">
                    <a href="{{ route('portal.solicitudes.show', $solicitud) }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-[.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Guardar corrección
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection