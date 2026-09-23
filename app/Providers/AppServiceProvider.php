<?php

namespace App\Providers;

use App\Models\Conversacion;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // El administrador (Natalia) tiene control total sobre todas las políticas.
        Gate::before(function ($user, $ability) {
            if ($user->esAdmin()) {
                return true;
            }

            return null;
        });

        // Notificación global de chats pendientes en el encabezado del administrador.
        ViewFacade::composer('layouts.app', function (View $view) {
            $pendientes = 0;

            if (optional(auth()->user())->esAdmin()) {
                $pendientes = Conversacion::where('estado', Conversacion::ESTADO_SOLICITADA)->count();
            }

            $view->with('chatPendientes', $pendientes);
        });

        // Aviso al administrador cuando hay aprendices que piden eliminar su solicitud.
        ViewFacade::composer('layouts.app', function (View $view) {
            $eliminaciones = 0;

            if (optional(auth()->user())->esAdmin()) {
                $eliminaciones = Solicitud::where('eliminacion_estado', Solicitud::ELIMINACION_SOLICITADA)->count();
            }

            $view->with('eliminacionesPendientes', $eliminaciones);
        });
    }
}