@php
    $estilos = [
        'Baja' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-400/20',
        'Media' => 'bg-lemon/15 text-amber-800 ring-lemon/50 dark:bg-lemon/10 dark:text-yellow-300 dark:ring-lemon/40',
        'Alta' => 'bg-amber-50 text-amber-700 ring-amber-600/30 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/30',
        'Crítica' => 'bg-rose-50 text-rose-700 ring-rose-600/30 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-400/30',
    ];
    $prioridad = $solicitud->prioridad ?? 'Baja';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $estilos[$prioridad] ?? $estilos['Baja'] }}">
    @if ($prioridad === 'Crítica')
        <i data-lucide="alert-octagon" class="h-3.5 w-3.5"></i>
    @elseif ($prioridad === 'Alta')
        <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i>
    @elseif ($prioridad === 'Media')
        <i data-lucide="activity" class="h-3.5 w-3.5"></i>
    @else
        <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
    @endif
    {{ $prioridad }}
</span>