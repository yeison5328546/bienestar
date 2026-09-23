<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Número de notificaciones sin leer (para la campana, sin recargar).
     */
    public function noLeidas()
    {
        return response()->json([
            'no_leidas' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Marca todas las notificaciones del aprendiz como leídas.
     */
    public function marcarLeidas()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}