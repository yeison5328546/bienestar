@php
    $esAdmin = auth()->user()->esAdmin();
    $estado = $conversacion?->estado ?? null;
    $urlMarcarLeidas = auth()->user()->esAprendiz() ? route('portal.notificaciones.marcar-leidas') : null;
    $plantillas = [
        'Hola, estamos revisando tu caso. Por favor acércate a la oficina de Bienestar al Aprendiz lo antes posible para solucionar el problema.',
        'Hola, gracias por escribirnos. ¿Podrías contarnos un poco más sobre cómo te estás sintiendo?',
        'Hola, queremos coordinar una atención contigo. Cuéntanos qué día y a qué hora te queda bien.',
        'Buena tarde, tu solicitud ya está siendo atendida por el equipo de Bienestar. Te escribiremos muy pronto.',
    ];
    $textoEstado = match ($estado) {
        'solicitada' => 'Pendiente de aprobación',
        'activa' => 'En curso',
        'cerrada' => 'Finalizado',
        default => 'Sin chat',
    };
    $estilosEstado = match ($estado) {
        'solicitada' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/30',
        'activa' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/30',
        'cerrada' => 'bg-slate-100 text-slate-600 ring-slate-600/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-400/30',
        default => 'bg-slate-100 text-slate-600 ring-slate-600/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-400/30',
    };
@endphp

<section id="chat-panel"
         class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/60"
         data-estado="{{ $estado }}"
         data-url-mensajes="{{ $mensajesUrl }}"
         data-url-enviar="{{ $enviarUrl }}"
         data-url-marcar="{{ $urlMarcarLeidas }}">

    {{-- Cabecera del chat --}}
    <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800 dark:bg-slate-900/40">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300">
                <i data-lucide="message-circle" class="h-5 w-5"></i>
            </span>
            <div>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Chat de atención y citación</h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Comunicación directa entre <span class="font-medium">{{ $esAdmin ? 'Natalia' : $solicitud->nombre_aprendiz }}</span> y el área de Bienestar.
                    <span class="ml-1 inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">
                        <i data-lucide="shield-check" class="h-3 w-3"></i>
                        Canal seguro SENA
                    </span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $estilosEstado }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $estado === 'activa' ? 'animate-pulse bg-current' : 'bg-current' }}"></span>
                {{ $textoEstado }}
            </span>

            {{-- Acciones del administrador: Natalia decide cuándo abrir la sesión --}}
            @if ($esAdmin)
                @if ($estado === 'activa')
                    <form method="POST" action="{{ route('solicitudes.chat.cerrar', $solicitud) }}"
                          onsubmit="return confirm('¿Finalizar el chat? La sesión se cerrará y el aprendiz ya no podrá escribir hasta que inicies una nueva sesión.')">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 shadow-sm transition hover:bg-rose-50 focus:outline-none focus:ring-4 focus:ring-rose-500/20 dark:border-rose-500/30 dark:bg-slate-800 dark:text-rose-400 dark:hover:bg-rose-500/10">
                            <i data-lucide="square-check" class="h-4 w-4"></i>
                            Finalizar Chat
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('solicitudes.chat.iniciar', $solicitud) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                            <i data-lucide="play" class="h-4 w-4"></i>
                            Iniciar Sesión de Chat
                        </button>
                    </form>
                @endif
            @else
                {{-- Acciones del aprendiz --}}
                @if ($estado === null || $estado === 'cerrada')
                    <form method="POST" action="{{ route('portal.solicitudes.chat.solicitar', $solicitud) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                            <i data-lucide="message-circle" class="h-4 w-4"></i>
                            {{ $estado === 'cerrada' ? 'Solicitar nuevo chat' : 'Solicitar chat / Cita con Bienestar' }}
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    {{-- Cuerpo del chat --}}
    @if ($estado === null)
        <div class="px-6 py-12 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                <i data-lucide="message-square-heart" class="h-7 w-7"></i>
            </span>
            <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">
                {{ $esAdmin ? 'Aún no hay un chat para esta solicitud' : 'Comunícate en vivo con Bienestar' }}
            </p>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                {{ $esAdmin
                    ? 'Puedes iniciar un chat directo con el aprendiz o esperar a que él lo solicite desde su portal.'
                    : 'Si quieres hablar con Natalia sobre tu caso, envía una solicitud de chat y te confirmarán en cuanto estén disponibles.' }}
            </p>
        </div>
    @elseif ($estado === 'solicitada')
        <div class="px-6 py-10 text-center">
            @if ($esAdmin)
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-300">
                    <i data-lucide="bell-ring" class="h-7 w-7"></i>
                </span>
                <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">El aprendiz solicita atención en vivo</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                    {{ $solicitud->nombre_aprendiz }} quiere hablar contigo sobre su solicitud. Pulsa "Iniciar Sesión de Chat" para comenzar la atención.
                </p>
            @else
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-300">
                    <i data-lucide="clock" class="h-7 w-7"></i>
                </span>
                <p class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">Solicitud de chat enviada</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                    El área de Bienestar revisará tu caso y te confirmará la conexión en cuanto estén disponibles. Te avisaremos con una campana y un correo. Esta página se actualiza automáticamente.
                </p>
            @endif
        </div>
    @else
        <div id="chat-mensajes" data-ultimo-id="{{ $conversacion->mensajes->max('id') ?? 0 }}"
             class="max-h-80 min-h-48 space-y-3 overflow-y-auto bg-slate-50/50 px-5 py-4 dark:bg-slate-950/40">
            @forelse ($conversacion->mensajes as $mensaje)
                <div class="flex {{ $mensaje->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[78%] {{ $mensaje->user_id === auth()->id() ? 'text-right' : '' }}">
                        <p class="mb-0.5 px-1 text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            {{ $mensaje->autor?->name ?? 'Sistema' }} · {{ $mensaje->created_at?->format('H:i') }}
                        </p>
                        <div class="{{ $mensaje->user_id === auth()->id()
                                ? 'rounded-2xl rounded-br-md bg-indigo-600 text-white'
                                : 'rounded-2xl rounded-bl-md border border-slate-200 bg-white text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200' }}
                            inline-block whitespace-pre-wrap px-4 py-2.5 text-left text-sm leading-relaxed shadow-sm">
                            {{ $mensaje->contenido }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="py-8 text-center text-sm text-slate-400 dark:text-slate-500">Sin mensajes todavía. Escribe el primero con el campo de abajo.</p>
            @endforelse
        </div>

        @if ($estado === 'cerrada')
            <div class="flex items-center gap-2.5 border-t border-slate-100 bg-slate-50/70 px-5 py-3 text-xs font-medium text-slate-500 dark:border-slate-800 dark:bg-slate-900/40 dark:text-slate-400">
                <i data-lucide="lock" class="h-3.5 w-3.5 shrink-0"></i>
                Chat finalizado {{ $conversacion->cerrada_at?->format('d/m/Y \a \l\a\s H:i') }}. El historial quedó archivado y es de solo lectura.
            </div>
        @else
            @if ($esAdmin)
                <div class="space-y-2 border-t border-slate-200 px-5 pb-2 pt-3 dark:border-slate-800">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Plantillas rápidas de citación</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($plantillas as $plantilla)
                            <button type="button" data-texto="{{ $plantilla }}"
                                    class="plantilla-chip inline-flex items-center gap-1 rounded-full border border-lemon/50 bg-lemon/15 px-3 py-1.5 text-xs font-medium text-amber-900 transition hover:bg-lemon/30 dark:border-lemon/30 dark:bg-lemon/10 dark:text-yellow-200 dark:hover:bg-lemon/20">
                                <i data-lucide="zap" class="h-3 w-3"></i>
                                {{ \Illuminate\Support\Str::limit($plantilla, 34) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <form id="chat-form" class="flex items-end gap-2 border-t border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900/60">
                @csrf
                <textarea id="chat-input" rows="1" maxlength="1000"
                          class="max-h-32 min-h-[44px] flex-1 resize-none rounded-xl border border-slate-300 bg-slate-50/60 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:bg-slate-950"
                          placeholder="{{ $esAdmin ? 'Escribe un mensaje o pega una plantilla... (Enter envía)' : 'Escribe tu mensaje... (Enter envía)' }}"></textarea>
                <button id="chat-enviar" type="submit"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-50">
                    <i data-lucide="send" class="h-5 w-5"></i>
                </button>
            </form>
        @endif
    @endif
</section>

<script>
    (function () {
        var panel = document.getElementById('chat-panel');
        if (!panel) return;

        var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
        var estado = panel.dataset.estado;
        var urlMensajes = panel.dataset.urlMensajes;
        var urlEnviar = panel.dataset.urlEnviar;
        var urlMarcar = panel.dataset.urlMarcar || null;
        var cont = document.getElementById('chat-mensajes');
        var ultimoId = cont ? (parseInt(cont.dataset.ultimoId || '0', 10) || 0) : 0;

        function burbuja(m) {
            var div = document.createElement('div');
            div.setAttribute('data-msg-id', m.id);
            div.className = 'flex ' + (m.propio ? 'justify-end' : 'justify-start');

            var inner = document.createElement('div');
            inner.className = 'max-w-[78%] ' + (m.propio ? 'text-right' : '');

            var meta = document.createElement('p');
            meta.className = 'mb-0.5 px-1 text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500';
            meta.textContent = m.autor + ' · ' + m.hora;
            inner.appendChild(meta);

            var b = document.createElement('div');
            b.className = 'inline-block whitespace-pre-wrap px-4 py-2.5 text-left text-sm leading-relaxed shadow-sm ' +
                (m.propio
                    ? 'rounded-2xl rounded-br-md bg-indigo-600 text-white'
                    : 'rounded-2xl rounded-bl-md border border-slate-200 bg-white text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200');
            b.textContent = m.contenido;
            inner.appendChild(b);

            div.appendChild(inner);
            return div;
        }

        function agregar(m) {
            if (!cont) return;
            cont.appendChild(burbuja(m));
            cont.dataset.ultimoId = m.id;
        }

        function scrollAbajo() {
            if (cont) cont.scrollTop = cont.scrollHeight;
        }

        function marcarLeidas() {
            if (!urlMarcar || !csrf) return;
            fetch(urlMarcar, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams()
            })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d.ok) {
                        var b = document.getElementById('notif-badge');
                        if (b) b.classList.add('hidden');
                    }
                })
                .catch(function () {});
        }

        function cargar() {
            fetch(urlMensajes, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.estado && data.estado !== panel.dataset.estado) {
                        window.location.reload();
                        return;
                    }
                    if (!cont) return;
                    var hubo = false;
                    (data.mensajes || []).forEach(function (m) {
                        if (m.id > ultimoId && !cont.querySelector('[data-msg-id="' + m.id + '"]')) {
                            agregar(m);
                            hubo = true;
                        }
                    });
                    if (hubo) {
                        scrollAbajo();
                        marcarLeidas();
                    }
                })
                .catch(function () {});
        }

        if (cont) scrollAbajo();

        if (estado === 'activa' || estado === 'solicitada') {
            window.setInterval(cargar, 5000);
        }

        var form = document.getElementById('chat-form');
        if (form) {
            var input = document.getElementById('chat-input');
            var btn = document.getElementById('chat-enviar');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var texto = input.value.trim();
                if (!texto) return;

                btn.disabled = true;
                fetch(urlEnviar, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ contenido: texto })
                })
                    .then(function (r) {
                        return r.json().then(function (d) { return { ok: r.ok, d: d }; });
                    })
                    .then(function (res) {
                        if (res.ok && res.d.ok) {
                            agregar(res.d.mensaje);
                            input.value = '';
                            input.style.height = '';
                            scrollAbajo();
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(function () { window.location.reload(); })
                    .finally(function () { btn.disabled = false; });
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.requestSubmit();
                }
            });
        }

        panel.querySelectorAll('.plantilla-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var ta = document.getElementById('chat-input');
                if (!ta) return;
                var base = ta.value.replace(/\s+$/, '');
                ta.value = base ? base + '\n' + chip.dataset.texto : chip.dataset.texto;
                ta.style.height = '';
                ta.style.height = Math.min(ta.scrollHeight, 128) + 'px';
                ta.focus();
            });
        });
    })();
</script>