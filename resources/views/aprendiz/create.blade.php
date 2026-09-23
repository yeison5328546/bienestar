@extends('layouts.aprendiz')

@section('titulo', 'Reportar solicitud')

@section('contenido')
    <div class="mx-auto flex max-w-2xl flex-col gap-6">
        <div>
            <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Volver a mis solicitudes
            </a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Reportar una solicitud</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Cuéntanos tu inquietud, queja o requerimiento. Lo gestionaremos con el área de Bienestar.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0"></i>
                <div>
                    <p class="font-semibold">Tus datos se completan automáticamente</p>
                    <p class="mt-0.5 text-xs opacity-90">Nombre, correo y programa/ficha se toman de tu cuenta: <span class="font-medium">{{ Auth::user()->name }}</span>@if (Auth::user()->documento) · Doc. {{ Auth::user()->documento }}@endif{{ Auth::user()->programa ? ' · '.Auth::user()->programa : '' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('portal.solicitudes.store') }}" class="space-y-5">
                @csrf
                @include('aprendiz._form')

                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end dark:border-slate-800">
                    <a href="{{ route('portal.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-[.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                        <i data-lucide="send" class="h-4 w-4"></i>
                        Enviar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection