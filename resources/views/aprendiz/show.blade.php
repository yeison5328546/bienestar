@extends('layouts.aprendiz')

@section('titulo', 'Detalle de mi solicitud')

@section('contenido')
    <div class="mx-auto flex max-w-3xl flex-col gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Volver a mis solicitudes
                </a>
                <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Detalle de tu solicitud</h1>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <x-estado :solicitud="$solicitud" />
                    @if ($solicitud->analizado)
                        <x-prioridad :solicitud="$solicitud" />
                    @endif
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ $solicitud->tipo_requerimiento }}
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                        <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                        Enviada el {{ $solicitud->created_at->format('d/m/Y \a \l\a\s H:i') }}
                    </span>
                </div>
            </div>

            @if ($solicitud->edicionDisponibleParaAprendiz())
                <a href="{{ route('portal.solicitudes.edit', $solicitud) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Corregir solicitud
                </a>
            @else
                <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500">
                    <i data-lucide="lock" class="h-4 w-4"></i>
                    Edición cerrada
                </span>
            @endif
        </div>

        @if ($solicitud->edicionDisponibleParaAprendiz())
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                <i data-lucide="timer" class="h-5 w-5 shrink-0"></i>
                Tienes <span class="font-semibold">&nbsp;{{ $solicitud->textoVentanaEdicion() }}&nbsp;</span> para corregir esta solicitud.
            </div>
        @else
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900/60">
                <i data-lucide="lock" class="h-5 w-5 shrink-0 text-slate-400"></i>
                La ventana de edición de 15 minutos ha finalizado. Para cambios, contáctate con el área de Bienestar.
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Nombre</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->nombre_aprendiz }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Documento</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->documento }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Correo</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Teléfono</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->telefono ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Programa de formación</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->programa ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ficha</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $solicitud->ficha ?? '—' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Descripción</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->descripcion }}</p>
                </div>
            </div>
        </div>

        {{-- Recomendaciones de la IA --}}
        @if ($solicitud->analizado)
            <div class="rounded-2xl border border-indigo-100 bg-indigo-50/50 p-6 shadow-sm sm:p-8 dark:border-indigo-500/20 dark:bg-indigo-500/[0.06]">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300">
                        <i data-lucide="sparkles" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Recomendaciones de Bienestar para ti</h2>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Estas orientaciones fueron generadas especialmente para tu caso.</p>
                    </div>
                </div>

                @if ($solicitud->esPrioritaria())
                    <div class="mt-4 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                        <i data-lucide="shield-check" class="h-5 w-5 shrink-0"></i>
                        <div>
                            <p class="font-semibold">Tu caso es importante para nosotros.</p>
                            <p class="mt-0.5 font-normal opacity-80">El equipo de Bienestar al Aprendiz ya fue notificado y te contactará pronto. Si necesitas atención inmediata, también puedes llamar a la Línea de Atención Psicosocial 106.</p>
                        </div>
                    </div>
                @endif

                @if ($solicitud->recomendaciones)
                    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $solicitud->recomendaciones }}</div>
                @else
                    <div class="mt-4 rounded-xl bg-white/70 p-4 text-sm leading-relaxed text-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                        Nos encontramos revisando tu caso para darte la mejor orientación posible. Mientras tanto, recuerda que puedes acercarte al área de Bienestar al Aprendiz: no estás solo y pedir ayuda es un paso valiente.
                        <form method="POST" action="{{ route('portal.solicitudes.ia', $solicitud) }}" class="mt-3">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 dark:border-indigo-500/30 dark:bg-slate-800 dark:text-indigo-300 dark:hover:bg-slate-700">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                Volver a intentar
                            </button>
                        </form>
                    </div>
                @endif

                @if ($solicitud->motivacion)
                    <p class="mt-4 rounded-xl bg-white/70 px-4 py-3 text-sm italic text-indigo-700 dark:bg-slate-900/60 dark:text-indigo-300">"{!! nl2br(e($solicitud->motivacion)) !!}"</p>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('portal.solicitudes.ia', $solicitud) }}" class="flex justify-center">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 dark:border-indigo-500/30 dark:bg-slate-800 dark:text-indigo-300 dark:hover:bg-slate-700">
                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                    Pedir recomendación a la IA
                </button>
            </form>
        @endif

        {{-- Solicitar eliminación de la solicitud --}}
        @if ($solicitud->puedeSolicitarEliminacion())
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <i data-lucide="user-round-x" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">¿Deseas eliminar esta solicitud?</h2>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Si ya no necesitas el servicio, envíanos tu petición con una breve explicación. El equipo de Bienestar la revisará y te comunicará la decisión.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('portal.solicitudes.eliminacion.solicitar', $solicitud) }}" class="mt-4">
                    @csrf
                    <label for="motivo" class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Explicación para Bienestar</label>
                    <textarea id="motivo" name="motivo" rows="3" required maxlength="1000"
                              class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-600 dark:bg-slate-950 dark:text-slate-200"
                              placeholder="Cuéntanos por qué deseas eliminar tu solicitud...">{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            Solicitar eliminación
                        </button>
                        <p class="text-xs text-slate-400 dark:text-slate-500">La decisión final la toma el área de Bienestar.</p>
                    </div>
                </form>
            </div>
        @elseif ($solicitud->peticionEliminacionPendiente())
            <div class="flex items-start gap-3 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm dark:border-violet-500/30 dark:bg-violet-500/10">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-300">
                    <i data-lucide="clock" class="h-4 w-4"></i>
                </span>
                <div>
                    <p class="font-semibold text-violet-900 dark:text-violet-200">Tu solicitud de eliminación está en revisión</p>
                    <p class="mt-0.5 text-violet-700/80 dark:text-violet-300/80">Enviaste tu petición {{ $solicitud->eliminacion_solicitada_at?->diffForHumans() }}. Bienestar debe decidir si la aprueba o no; te avisaremos por las notificaciones cuando haya respuesta.</p>
                    @if ($solicitud->eliminacion_motivo)
                        <p class="mt-2 rounded-lg bg-white/70 px-3 py-2 text-xs italic text-violet-800 dark:bg-slate-900/60 dark:text-violet-300">Tu explicación: "{!! nl2br(e($solicitud->eliminacion_motivo)) !!}"</p>
                    @endif
                </div>
            </div>
        @elseif ($solicitud->peticionEliminacionRechazada())
            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800/60">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                </span>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-slate-200">Bienestar decidió mantener tu solicitud activa</p>
                    <p class="mt-0.5 text-slate-500 dark:text-slate-400">Tu petición de eliminación fue revisada {{ $solicitud->eliminacion_decidida_at?->diffForHumans() }} y el equipo concluyó que tu caso debe seguir en atención. Si deseas conversarlo, escríbenos por el chat.</p>
                </div>
            </div>
        @endif

        {{-- Chat y atención en vivo con Bienestar --}}
        @include('chat.panel', [
            'solicitud' => $solicitud,
            'conversacion' => $conversacion ?? null,
            'mensajesUrl' => route('portal.solicitudes.chat.mensajes', $solicitud),
            'enviarUrl' => route('portal.solicitudes.chat.enviar', $solicitud),
        ])
    </div>
@endsection