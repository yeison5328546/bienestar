@extends('layouts.app')

@section('titulo', 'Registrar aprendiz')

@section('contenido')
    <div class="mx-auto flex max-w-3xl flex-col gap-6">
        <div>
            <a href="{{ route('aprendices.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Volver a aprendices
            </a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Registrar aprendiz</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Crea la cuenta de acceso del aprendiz al portal de Bienestar.</p>
        </div>

        @php
            $base = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:outline-none focus:ring-4 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500';
            $ok = 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/10 dark:border-slate-700';
            $error = 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 dark:border-rose-500/60';
        @endphp

        <form method="POST" action="{{ route('aprendices.store') }}"
              class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900/60">
            @csrf

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <i data-lucide="user" class="h-4 w-4"></i>
                    </span>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Datos del aprendiz</h2>
                </div>
                <p class="mt-1 pl-9 text-xs text-slate-500 dark:text-slate-400">Identificación y contacto con el que el aprendiz ingresará al portal.</p>

                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre completo</label>
                        <input id="name" type="text" name="name" maxlength="255" value="{{ old('name') }}"
                               class="{{ $base }} {{ $errors->has('name') ? $error : $ok }}"
                               placeholder="Nombres y apellidos">
                        @error('name')
                            <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="documento" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Documento de identidad</label>
                        <input id="documento" type="text" name="documento" maxlength="50" value="{{ old('documento') }}"
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
                            <input id="email" type="email" name="email" maxlength="255" value="{{ old('email') }}"
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
                            <input id="telefono" type="text" name="telefono" maxlength="30" value="{{ old('telefono') }}"
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
                        <input id="programa" type="text" name="programa" maxlength="255" value="{{ old('programa') }}"
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
                        <input id="ficha" type="text" name="ficha" maxlength="50" value="{{ old('ficha') }}"
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

            <div class="mt-7">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <i data-lucide="lock" class="h-4 w-4"></i>
                    </span>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Acceso al portal</h2>
                </div>
                <p class="mt-1 pl-9 text-xs text-slate-500 dark:text-slate-400">Contraseña con la que el aprendiz iniciará sesión en el portal.</p>

                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Contraseña</label>
                        <div class="relative">
                            <i data-lucide="key-round" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input id="password" type="password" name="password" minlength="6"
                                   class="{{ $base }} {{ $errors->has('password') ? $error : $ok }} pl-10"
                                   placeholder="Mínimo 6 caracteres">
                        </div>
                        @error('password')
                            <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
                                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                        <div class="relative">
                            <i data-lucide="key-round" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation" minlength="6"
                                   class="{{ $base }} {{ $errors->has('password_confirmation') ? $error : $ok }} pl-10"
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end dark:border-slate-800">
                <a href="{{ route('aprendices.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-[.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                    <i data-lucide="user-plus" class="h-4 w-4"></i>
                    Registrar aprendiz
                </button>
            </div>
        </form>
    </div>
@endsection