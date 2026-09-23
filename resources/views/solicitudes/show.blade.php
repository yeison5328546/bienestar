@extends('layouts.app')

@section('titulo', 'Detalle de solicitud')

@section('contenido')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <a href="{{ route('solicitudes.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Volver a solicitudes
                </a>
                <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $solicitud->nombre_aprendiz }}</h1>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <x-estado :solicitud="$solicitud" />
                    <x-prioridad :solicitud="$solicitud" />
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ $solicitud->tipo_requerimiento }}
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                        <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                        Registrada el {{ $solicitud->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('solicitudes.edit', $solicitud) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Editar
                </a>
            </div>
        </div>

        @if ($solicitud->esPrioritaria())
            <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300">
                    <i data-lucide="bell-ring" class="h-4 w-4"></i>
                </span>
                <div>
                    <p class="font-semibold">Prioridad detectada por la IA — {{ $solicitud->prioridad }}</p>
                    <p class="mt-0.5 font-normal opacity-80">Natalia fue notificada para atender este caso con urgencia.</p>
                </div>
            </div>
        @endif

        @if ($solicitud->peticionEliminacionPendiente())
            <div class="rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm dark:border-violet-500/30 dark:bg-violet-500/[0.07]">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-300">
                            <i data-lucide="user-round-x" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">El aprendiz solicita eliminar esta solicitud</h2>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                Solicitado {{ $solicitud->eliminacion_solicitada_at?->diffForHumans() }} ·
                                <span class="font-medium text-violet-700 dark:text-violet-300">{{ $solicitud->nombre_aprendiz }}</span>
                            </p>
                            <div class="mt-3 max-w-2xl rounded-xl border border-violet-200 bg-white/80 p-4 dark:border-violet-500/20 dark:bg-slate-900/60">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Explicación del aprendiz</p>
                                <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->eliminacion_motivo }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-col gap-2.5 lg:w-64">
                        <form action="{{ route('solicitudes.eliminacion.aprobar', $solicitud) }}" method="POST"
                              onsubmit="return confirm('¿Aprobar la petición y eliminar esta solicitud de forma permanente? El aprendiz solo podrá crear una nueva solicitud si la necesita.')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95">
                                <i data-lucide="check" class="h-4 w-4"></i>
                                Aprobar y eliminar
                            </button>
                        </form>
                        <form action="{{ route('solicitudes.eliminacion.rechazar', $solicitud) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-400/20 active:scale-95 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                <i data-lucide="shield-check" class="h-4 w-4"></i>
                                Rechazar petición
                            </button>
                        </form>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Al aprobar, la solicitud se elimina de forma permanente. Al rechazar, el aprendiz será notificado y su caso sigue activo.</p>
                    </div>
                </div>
            </div>
        @elseif ($solicitud->peticionEliminacionRechazada())
            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800/60">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">
                    <i data-lucide="ban" class="h-4 w-4"></i>
                </span>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-slate-200">Petición de eliminación rechazada</p>
                    <p class="mt-0.5 text-slate-500 dark:text-slate-400">Decisión tomada {{ $solicitud->eliminacion_decidida_at?->diffForHumans() }}. La solicitud sigue activa y el aprendiz fue notificado.
                        @if ($solicitud->eliminacion_motivo)
                            Explicación del aprendiz: <span class="italic">"{{ $solicitud->eliminacion_motivo }}"</span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5 xl:gap-8">
            {{-- Columna izquierda: expediente y análisis --}}
            <div class="space-y-6 lg:col-span-3">
                {{-- Panel de análisis de la IA --}}
                <div class="card-lift animate-fade-in-up rounded-2xl border border-indigo-100 bg-indigo-50/40 p-6 shadow-sm sm:p-8 dark:border-indigo-500/20 dark:bg-indigo-500/[0.06]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300">
                                <i data-lucide="sparkles" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Análisis de la IA — Bienestar al Aprendiz</h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Etiquetado automático de prioridad y sugerencia de gestión del caso.</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('solicitudes.ia', $solicitud) }}" class="shrink-0">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-white px-4 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 dark:border-indigo-500/30 dark:bg-slate-800 dark:text-indigo-300 dark:hover:bg-slate-700">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                {{ $solicitud->analizado ? 'Recalcular con IA' : 'Analizar con IA' }}
                            </button>
                        </form>
                    </div>

                    @if ($solicitud->analizado)
                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Prioridad</p>
                                <div class="mt-1.5"><x-prioridad :solicitud="$solicitud" /></div>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Etiqueta</p>
                                <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->etiqueta }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">¿Alerta?</p>
                                <p class="mt-1.5 text-sm font-medium {{ $solicitud->esPrioritaria() ? 'text-rose-700 dark:text-rose-300' : 'text-slate-600 dark:text-slate-300' }}">
                                    {{ $solicitud->esPrioritaria() ? 'Sí — caso delicado' : 'No' }}
                                </p>
                            </div>
                        </div>

                        @if ($solicitud->gestion_admin)
                            <div class="mt-5 rounded-xl border border-indigo-200 bg-white p-4 dark:border-indigo-500/20 dark:bg-slate-900/70">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Sugerencia de gestión</p>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->gestion_admin }}</p>
                            </div>
                        @else
                            <div class="mt-5 rounded-xl border border-indigo-200 bg-white p-4 text-sm text-slate-500 dark:border-indigo-500/20 dark:bg-slate-900/70 dark:text-slate-400">
                                Esta solicitud ya fue analizada, pero el análisis no incluyó una sugerencia de gestión. Puedes recalcular el análisis con el botón de arriba.
                            </div>
                        @endif

                        @if ($solicitud->recomendaciones)
                            <div class="mt-3 rounded-xl border border-indigo-200 bg-white p-4 dark:border-indigo-500/20 dark:bg-slate-900/70">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Recomendación que verá el aprendiz</p>
                                <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->recomendaciones }}</p>
                            </div>
                        @endif

                        @if ($solicitud->motivacion)
                            <div class="mt-3 rounded-xl border border-indigo-100 bg-white p-4 dark:border-indigo-500/15 dark:bg-slate-900/70">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mensaje de motivación para el aprendiz</p>
                                <p class="mt-1.5 text-sm italic text-indigo-700 dark:text-indigo-300">"{!! nl2br(e($solicitud->motivacion)) !!}"</p>
                            </div>
                        @endif
                    @else
                        <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Esta solicitud aún no ha sido analizada por la IA. Haz clic en el botón de arriba para generar el análisis, la etiqueta y las recomendaciones para el aprendiz.</p>
                    @endif
                </div>

                {{-- Ficha técnica --}}
                <div class="card-lift rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-white">
                        <i data-lucide="id-card" class="h-4 w-4 text-indigo-600 dark:text-indigo-400"></i>
                        Ficha técnica del aprendiz
                    </h2>
                    <div class="mt-5 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="hash" class="h-3.5 w-3.5"></i>
                                Documento de identidad
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->documento }}</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="mail" class="h-3.5 w-3.5"></i>
                                Correo electrónico
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->email ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="phone" class="h-3.5 w-3.5"></i>
                                Teléfono
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                                Programa de formación
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->programa ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="hash" class="h-3.5 w-3.5"></i>
                                Ficha
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->ficha ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="tag" class="h-3.5 w-3.5"></i>
                                Tipo de requerimiento
                            </p>
                            <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->tipo_requerimiento }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <i data-lucide="file-text" class="h-3.5 w-3.5"></i>
                                Descripción del caso
                            </p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->descripcion }}</p>
                        </div>
                    </div>
                </div>

                {{-- Cambiar estado --}}
                <div class="card-lift rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                                <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Cambiar estado del caso</h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Actualiza el seguimiento de esta solicitud.</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('solicitudes.estado', $solicitud) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            @csrf
                            @method('PATCH')
                            <select name="estado" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 sm:w-auto dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @foreach (\App\Models\Solicitud::ESTADOS as $estado)
                                    <option value="{{ $estado }}" @selected($solicitud->estado === $estado)>{{ $estado }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                                <i data-lucide="check" class="h-4 w-4"></i>
                                Actualizar estado
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: chat de atención y citación --}}
            <div class="lg:col-span-2">
                <div class="lg:sticky lg:top-24">
                    @include('chat.panel', [
                        'solicitud' => $solicitud,
                        'conversacion' => $conversacion ?? null,
                        'mensajesUrl' => route('solicitudes.chat.mensajes', $solicitud),
                        'enviarUrl' => route('solicitudes.chat.enviar', $solicitud),
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection