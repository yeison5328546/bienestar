<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Bienestar al Aprendiz SENA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex min-h-screen flex-col justify-between overflow-x-hidden bg-slate-50 font-sans text-slate-800 antialiased selection:bg-sena/20 selection:text-sena">

    {{-- Fondo atmosférico institucional --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden bg-gradient-to-br from-[#f2fcf4] via-[#eaf7ee] to-[#ffffff] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="absolute -left-36 -top-36 h-[36rem] w-[36rem] animate-float-slow rounded-full bg-sena/12 blur-3xl"></div>
        <div class="absolute -right-28 top-1/3 h-[32rem] w-[32rem] animate-float-reverse rounded-full bg-lemon/10 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/4 h-[40rem] w-[40rem] animate-float-slow rounded-full bg-sena/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#39b54a0a_1px,transparent_1px),linear-gradient(to_bottom,#39b54a0a_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
    </div>

    <button type="button" id="tema-toggle" aria-label="Cambiar tema"
            class="fixed right-4 top-4 z-30 inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/70 p-2.5 text-slate-600 shadow-sm backdrop-blur transition hover:bg-white focus:outline-none focus:ring-4 focus:ring-sena/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">
        <i data-lucide="moon" class="block h-5 w-5 dark:hidden"></i>
        <i data-lucide="sun" class="hidden h-5 w-5 dark:block"></i>
    </button>

    {{-- Barra superior --}}
    <header class="relative z-10 w-full">
        <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sena text-white shadow-md shadow-sena/20">
                    <i data-lucide="heart-pulse" class="h-5 w-5"></i>
                </span>
                <span class="flex flex-col">
                    <span class="font-display text-xl font-extrabold tracking-tight text-sena">Bienestar al Aprendiz</span>
                    <span class="text-[10px] font-semibold tracking-wider text-slate-500">SENA · COLOMBIA</span>
                </span>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <div class="hidden items-center gap-1.5 rounded-full border border-slate-200/80 bg-white/70 px-3 py-1.5 text-slate-600 shadow-sm backdrop-blur-sm sm:flex">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-sena"></span>
                    <span class="font-medium">Servidor Oficial en Línea</span>
                </div>
                <div class="flex items-center gap-1 font-medium text-slate-500">
                    <i data-lucide="lock" class="h-4 w-4 text-sena"></i>
                    <span class="hidden text-xs md:inline">Conexión Segura SSL/TLS 256-bit</span>
                </div>
            </div>
        </nav>
    </header>

    {{-- Contenedor principal --}}
    <main class="relative z-10 flex flex-1 items-center justify-center px-4 py-8 md:py-12">
        <div class="w-full max-w-[490px] animate-fade-in-up">
            <div class="rounded-3xl border border-emerald-100/90 bg-white/95 p-8 shadow-2xl shadow-sena-950/10 backdrop-blur-xl transition-all duration-300 hover:shadow-sena-950/15 sm:p-10 dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-slate-950/40">

                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 inline-flex items-center gap-2 rounded-full border border-sena/20 bg-sena/10 px-3.5 py-1.5 text-xs font-semibold text-sena shadow-sm dark:bg-sena/15 dark:text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-sena"></span>
                        Acceso al portal
                    </div>
                    <h1 class="font-display text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white sm:text-3xl">
                        Hola, bienvenido(a) <span class="inline-block cursor-default transition-transform duration-200 hover:rotate-12">👋</span>
                    </h1>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        Ingresa al panel de Bienestar al Aprendiz SENA con tus credenciales institucionales.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                        <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
                        <div>
                            <p class="font-semibold">No se pudo iniciar sesión.</p>
                            <ul class="mt-1 list-disc space-y-0.5 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold tracking-wide text-slate-700 dark:text-slate-300">Correo electrónico institucional</label>
                        <div class="custom-input flex items-center rounded-xl border border-slate-200 bg-slate-50/80 transition-all duration-200 focus-within:border-sena focus-within:bg-white dark:border-slate-700 dark:bg-slate-950/60 dark:focus-within:bg-slate-950">
                            <div class="flex items-center pl-3.5 pr-1 text-slate-400">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   placeholder="usuario@sena.edu.co"
                                   class="w-full border-0 bg-transparent px-2 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-0 dark:text-slate-100 dark:placeholder-slate-500 @error('email') placeholder-rose-400 @enderror">
                            @if (old('email'))
                                <div class="flex items-center pr-3">
                                    <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase text-sena">Ok</span>
                                </div>
                            @endif
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold tracking-wide text-slate-700 dark:text-slate-300">Contraseña de acceso</label>
                        <div class="custom-input flex items-center rounded-xl border border-slate-200 bg-slate-50/80 transition-all duration-200 focus-within:border-sena focus-within:bg-white dark:border-slate-700 dark:bg-slate-950/60 dark:focus-within:bg-slate-950">
                            <div class="flex items-center pl-3.5 pr-1 text-slate-400">
                                <i data-lucide="lock-keyhole" class="h-5 w-5"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   placeholder="••••••••••••"
                                   class="w-full border-0 bg-transparent px-2 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-0 dark:text-slate-100 dark:placeholder-slate-500">
                            <button type="button" aria-label="Mostrar u ocultar contraseña" id="toggle-password"
                                    class="p-2 text-slate-400 transition-colors hover:text-slate-600 focus:outline-none dark:hover:text-slate-300">
                                <i data-lucide="eye" class="h-5 w-5" id="password-icon-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1 text-xs">
                        <p class="text-xs text-slate-500 dark:text-slate-400">Recuerda cerrar sesión al terminar.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="group flex w-full items-center justify-center gap-2 rounded-xl bg-sena px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-sena/30 transition-all duration-200 hover:bg-sena-dark hover:shadow-lg hover:shadow-sena/40 focus:outline-none focus:ring-2 focus:ring-sena focus:ring-offset-2 active:scale-[.98]">
                            <span>Ingresar al Sistema</span>
                            <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-6 flex items-start gap-3 rounded-xl border border-slate-200/60 bg-slate-50/70 p-3 text-[11px] leading-tight text-slate-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-400">
                    <i data-lucide="shield-check" class="mt-0.5 h-5 w-5 shrink-0 text-sena"></i>
                    <p>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">Inicio de sesión seguro:</span>
                        tus credenciales se transmiten por conexión cifrada y solo las usa el área de Bienestar del SENA.
                    </p>
                </div>

                <div class="mt-6 space-y-3 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('registro') }}" class="font-semibold text-sena transition hover:text-sena-dark hover:underline">Regístrate aquí</a>
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        Área de Bienestar al Aprendiz · <span class="font-semibold text-sena">SENA</span>
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between px-4 text-xs text-slate-400 dark:text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Regional Distrito Capital · Sede Central
                </span>
                <span>Portal oficial SENA</span>
            </div>
        </div>
    </main>

    {{-- Pie institucional --}}
    <footer class="relative z-10 w-full py-6 text-center">
        <p class="font-display text-sm font-bold text-sena">Portal Institucional SENA — Dirección de Formación Profesional</p>
        <p class="mx-auto mt-1 max-w-3xl px-4 text-xs text-slate-500 dark:text-slate-400">
            © {{ date('Y') }} Servicio Nacional de Aprendizaje SENA - Dirección de Formación Profesional - Bienestar al Aprendiz
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('toggle-password');
            var input = document.getElementById('password');
            var eye = document.getElementById('password-icon-eye');
            if (btn && input && eye) {
                btn.addEventListener('click', function () {
                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    if (eye) { eye.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye'); }
                    if (window.lucide) { lucide.createIcons(); }
                });
            }
        });
    </script>
</body>
</html>