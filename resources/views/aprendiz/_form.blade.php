@php
    $tipos = App\Models\Solicitud::TIPOS;

    $base = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:outline-none focus:ring-4 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500';
    $ok = 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/10 dark:border-slate-700';
    $fx = 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 dark:border-rose-500/60';
@endphp

<div class="space-y-5">
    @if (!Auth::user()->documento)
        <div>
            <label for="documento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Número de documento</label>
            <div class="relative">
                <i data-lucide="id-card" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="documento" type="text" name="documento" maxlength="50" value="{{ old('documento') }}"
                       class="{{ $base }} {{ $errors->has('documento') ? $fx : $ok }} pl-10"
                       placeholder="Ej: 1000000001">
            </div>
            @error('documento')
                <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                    <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                </p>
            @enderror
        </div>
    @endif

    <div>
        <label for="tipo_requerimiento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Tipo de requerimiento</label>
        <select id="tipo_requerimiento" name="tipo_requerimiento"
                class="{{ $base }} {{ $errors->has('tipo_requerimiento') ? $fx : $ok }}">
            <option value="" disabled @selected(empty(old('tipo_requerimiento', $solicitud->tipo_requerimiento ?? '')))>Selecciona el tipo...</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo }}" @selected(old('tipo_requerimiento', $solicitud->tipo_requerimiento ?? '') === $tipo)>{{ $tipo }}</option>
            @endforeach
        </select>
        @error('tipo_requerimiento')
            <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="telefono" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Teléfono de contacto (opcional)</label>
        <div class="relative">
            <i data-lucide="phone" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            <input id="telefono" type="text" name="telefono" maxlength="30" value="{{ old('telefono', $solicitud->telefono ?? '') }}"
                   class="{{ $base }} {{ $errors->has('telefono') ? $fx : $ok }} pl-10"
                   placeholder="Ej: 3001234567">
        </div>
        @error('telefono')
            <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="descripcion" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Describe tu solicitud</label>
        <textarea id="descripcion" name="descripcion" rows="6" maxlength="5000"
                  class="{{ $base }} {{ $errors->has('descripcion') ? $fx : $ok }} resize-none"
                  placeholder="Cuéntanos de forma clara y detallada qué necesitas o cuál es tu problema...">{{ old('descripcion', $solicitud->descripcion ?? '') }}</textarea>
        <div class="mt-1.5 flex items-center justify-between">
            @error('descripcion')
                <p class="flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                    <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                </p>
            @enderror
            <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">Máx. 5000 caracteres</span>
        </div>
    </div>
</div>