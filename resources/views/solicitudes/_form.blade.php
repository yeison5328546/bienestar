@php
    $tipos = App\Models\Solicitud::TIPOS;
    $estados = App\Models\Solicitud::ESTADOS;

    $base = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:outline-none focus:ring-4 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500';
    $ok = 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/10 dark:border-slate-700';
    $error = 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 dark:border-rose-500/60';
@endphp

<div class="space-y-7">
    <div>
        <div class="flex items-center gap-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                <i data-lucide="user" class="h-4 w-4"></i>
            </span>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Información del aprendiz</h2>
        </div>
        <p class="mt-1 pl-9 text-xs text-slate-500 dark:text-slate-400">Datos de identificación y contacto del aprendiz.</p>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="nombre_aprendiz" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre completo</label>
                <input id="nombre_aprendiz" type="text" name="nombre_aprendiz" value="{{ old('nombre_aprendiz', $solicitud->nombre_aprendiz ?? '') }}"
                       class="{{ $base }} {{ $errors->has('nombre_aprendiz') ? $error : $ok }}"
                       placeholder="Nombres y apellidos">
                @error('nombre_aprendiz')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="documento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Documento de identidad</label>
                <input id="documento" type="text" name="documento" maxlength="50" value="{{ old('documento', $solicitud->documento ?? '') }}"
                       class="{{ $base }} {{ $errors->has('documento') ? $error : $ok }}"
                       placeholder="Ej: 1000123456">
                @error('documento')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Correo electrónico</label>
                <div class="relative">
                    <i data-lucide="mail" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="email" type="email" name="email" maxlength="255" value="{{ old('email', $solicitud->email ?? '') }}"
                           class="{{ $base }} {{ $errors->has('email') ? $error : $ok }} pl-10"
                           placeholder="aprendiz@soy.sena.edu.co">
                </div>
                @error('email')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="telefono" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Teléfono</label>
                <div class="relative">
                    <i data-lucide="phone" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="telefono" type="text" name="telefono" maxlength="30" value="{{ old('telefono', $solicitud->telefono ?? '') }}"
                           class="{{ $base }} {{ $errors->has('telefono') ? $error : $ok }} pl-10"
                           placeholder="Ej: 3001234567">
                </div>
                @error('telefono')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="programa" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Programa de formación</label>
                <input id="programa" type="text" name="programa" maxlength="255" value="{{ old('programa', $solicitud->programa ?? '') }}"
                       class="{{ $base }} {{ $errors->has('programa') ? $error : $ok }}"
                       placeholder="Ej: Análisis y Desarrollo de Software">
                @error('programa')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="ficha" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Ficha</label>
                <input id="ficha" type="text" name="ficha" maxlength="50" value="{{ old('ficha', $solicitud->ficha ?? '') }}"
                       class="{{ $base }} {{ $errors->has('ficha') ? $error : $ok }}"
                       placeholder="Ej: 2754123">
                @error('ficha')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <div class="flex items-center gap-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                <i data-lucide="file-text" class="h-4 w-4"></i>
            </span>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Detalles del requerimiento</h2>
        </div>
        <p class="mt-1 pl-9 text-xs text-slate-500 dark:text-slate-400">Tipo, estado y descripción del caso.</p>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="tipo_requerimiento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Tipo de requerimiento</label>
                <select id="tipo_requerimiento" name="tipo_requerimiento"
                        class="{{ $base }} {{ $errors->has('tipo_requerimiento') ? $error : $ok }}">
                    <option value="" disabled @selected(empty(old('tipo_requerimiento', $solicitud->tipo_requerimiento ?? '')))>Seleccione un tipo...</option>
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
                <label for="estado" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Estado de la solicitud</label>
                <select id="estado" name="estado"
                        class="{{ $base }} {{ $errors->has('estado') ? $error : $ok }}">
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" @selected(old('estado', $solicitud->estado ?? 'Pendiente') === $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
                @error('estado')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="descripcion" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Descripción del caso</label>
                <textarea id="descripcion" name="descripcion" rows="5" maxlength="5000"
                          class="{{ $base }} {{ $errors->has('descripcion') ? $error : $ok }} resize-none"
                          placeholder="Describa de forma clara y detallada el motivo de la solicitud...">{{ old('descripcion', $solicitud->descripcion ?? '') }}</textarea>
                @error('descripcion')
                    <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>
</div>