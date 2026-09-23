@extends('layouts.app')

@section('titulo', 'Editar solicitud')

@section('contenido')
    <div class="mx-auto flex max-w-3xl flex-col gap-6">
        <div>
            <a href="{{ route('solicitudes.show', $solicitud) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Volver al detalle
            </a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Editar solicitud</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Actualiza la información del caso de {{ $solicitud->nombre_aprendiz }}.</p>
        </div>

        <form method="POST" action="{{ route('solicitudes.update', $solicitud) }}"
              class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
            @csrf
            @method('PUT')
            @include('solicitudes._form', ['solicitud' => $solicitud])

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end dark:border-slate-800">
                <a href="{{ route('solicitudes.show', $solicitud) }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-[.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection