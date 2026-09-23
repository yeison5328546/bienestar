<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta · Bienestar al Aprendiz</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased dark:bg-slate-950 dark:text-slate-300">

    <button type="button" id="tema-toggle" aria-label="Cambiar tema"
            class="fixed right-4 top-4 z-20 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">
        <i data-lucide="moon" class="block h-5 w-5 dark:hidden"></i>
        <i data-lucide="sun" class="hidden h-5 w-5 dark:block"></i>
    </button>

    <div class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6">
        <div class="w-full max-w-xl">
            <div class="mb-8 flex flex-col items-center text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-lg shadow-indigo-600/20">
                    <i data-lucide="heart-pulse" class="h-7 w-7"></i>
                </span>
                <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Crea tu cuenta de aprendiz</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Regístrate y reporta tus solicitudes sin necesidad de acudir presencialmente.</p>
            </div>

            <div class="animate-fade-in-up rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                        <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
                        <span class="flex-1">
                            <span class="font-semibold">Revisa los campos del formulario.</span>
                            <ul class="mt-1 list-disc space-y-0.5 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </span>
                    </div>
                @endif

                @php
                    $base = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:outline-none focus:ring-4 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500';
                    $ok = 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/10 dark:border-slate-700';
                    $fx = 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 dark:border-rose-500/60';
                @endphp

                <form method="POST" action="{{ route('registro.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="{{ $base }} {{ $errors->has('name') ? $fx : $ok }}"
                               placeholder="Nombres y apellidos">
                        @error('name')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="documento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Documento de identidad</label>
                            <input id="documento" type="text" name="documento" value="{{ old('documento') }}" required maxlength="50"
                                   class="{{ $base }} {{ $errors->has('documento') ? $fx : $ok }}"
                                   placeholder="Ej: 1000123456">
                            @error('documento')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Correo electrónico</label>
                            <div class="relative">
                                <i data-lucide="mail" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="{{ $base }} {{ $errors->has('email') ? $fx : $ok }} pl-10"
                                       placeholder="correo@sena.edu.co">
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="programa" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Programa de formación</label>
                            <input id="programa" type="text" name="programa" value="{{ old('programa') }}" maxlength="255"
                                   class="{{ $base }} {{ $errors->has('programa') ? $fx : $ok }}"
                                   placeholder="Ej: Análisis y Desarrollo de Software">
                            @error('programa')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ficha" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Ficha</label>
                            <input id="ficha" type="text" name="ficha" value="{{ old('ficha') }}" maxlength="50"
                                   class="{{ $base }} {{ $errors->has('ficha') ? $fx : $ok }}"
                                   placeholder="Ej: 2754123">
                            @error('ficha')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Contraseña</label>
                            <div class="relative">
                                <i data-lucide="lock-keyhole" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input id="password" type="password" name="password" required autocomplete="new-password" minlength="8"
                                       class="{{ $base }} {{ $errors->has('password') ? $fx : $ok }} pl-10"
                                       placeholder="Mínimo 8 caracteres">
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                            <div class="relative">
                                <i data-lucide="lock-keyhole" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                       class="{{ $base }} pl-10"
                                       placeholder="Repite tu contraseña">
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 active:scale-[.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                        Crear mi cuenta
                        <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5"></i>
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">Inicia sesión</a>
            </p>
        </div>
    </div>
</body>
</html>