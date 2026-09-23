<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\Solicitud;
use App\Notifications\NuevoMensajeDeBienestar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use AuthorizesRequests;
    /**
     * El aprendiz solicita atención en vivo para una de sus solicitudes.
     * La conversación queda en estado "solicitada" y Natalia recibe la notificación.
     */
    public function solicitar(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        if ($solicitud->conversaciones()->whereIn('estado', [Conversacion::ESTADO_SOLICITADA, Conversacion::ESTADO_ACTIVA])->exists()) {
            return back()->with('error', 'Ya tienes un chat pendiente o en curso para esta solicitud.');
        }

        $solicitud->conversaciones()->create([
            'iniciada_por' => 'aprendiz',
            'estado' => Conversacion::ESTADO_SOLICITADA,
        ]);

        return back()->with('exito', 'Solicitud de chat enviada a Bienestar. Te confirmaremos en cuanto estén listos para atenderte.');
    }

    /**
     * Natalia inicia un chat (o acepta la solicitud pendiente del aprendiz).
     * Solo el administrador puede ejecutar esta acción.
     */
    public function iniciar(Solicitud $solicitud)
    {
        $conv = $solicitud->conversaciones()
            ->where('estado', Conversacion::ESTADO_SOLICITADA)
            ->latest()
            ->first();

        if ($conv) {
            $conv->update([
                'estado' => Conversacion::ESTADO_ACTIVA,
                'iniciada_at' => now(),
                'iniciada_por' => 'admin',
            ]);
        } else {
            $solicitud->conversaciones()->create([
                'iniciada_por' => 'admin',
                'estado' => Conversacion::ESTADO_ACTIVA,
                'iniciada_at' => now(),
            ]);
        }

        $this->notificarAprendiz(
            $solicitud,
            'Natalia inició una sesión de chat para atender tu solicitud',
            'Tu chat con Bienestar al Aprendiz está abierto. Escríbenos cuando puedas.',
            'citacion'
        );

        return back()->with('exito', 'Chat iniciado con el aprendiz. Puedes enviar tu primer mensaje o usar una plantilla de citación.');
    }

    /**
     * Natalia cierra el chat. El historial queda guardado en modo solo lectura.
     */
    public function cerrar(Solicitud $solicitud)
    {
        $conv = $solicitud->conversaciones()
            ->where('estado', Conversacion::ESTADO_ACTIVA)
            ->latest()
            ->first();

        if (! $conv) {
            return back()->with('error', 'No hay un chat activo para cerrar.');
        }

        $conv->update([
            'estado' => Conversacion::ESTADO_CERRADA,
            'cerrada_at' => now(),
            'cerrada_por' => auth()->id(),
        ]);

        return back()->with('exito', 'Chat finalizado. El historial quedó archivado junto a la solicitud para auditoría.');
    }

    /**
     * Envía un mensaje. Solo válido mientras el chat está activo.
     */
    public function enviar(Request $request, Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $datos = $request->validate([
            'contenido' => ['required', 'string', 'max:1000'],
        ]);

        $conv = $solicitud->conversaciones()
            ->where('estado', Conversacion::ESTADO_ACTIVA)
            ->latest()
            ->first();

        if (! $conv) {
            return response()->json(['ok' => false, 'mensaje' => 'El chat no está activo.'], 422);
        }

        $mensaje = $conv->mensajes()->create([
            'user_id' => auth()->id(),
            'contenido' => trim($datos['contenido']),
        ]);

        if (auth()->user()->esAdmin()) {
            $this->notificarAprendiz(
                $solicitud,
                'Nuevo mensaje de Natalia',
                $mensaje->contenido,
                'mensaje'
            );
        }

        return response()->json(['ok' => true, 'mensaje' => $this->formatear($mensaje)]);
    }

    /**
     * Devuelve el estado y los mensajes de la última conversación (para polling).
     */
    public function mensajes(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $conv = $solicitud->conversaciones()->latest()->first();

        if (! $conv) {
            return response()->json(['estado' => null, 'mensajes' => []]);
        }

        $mensajes = $conv->mensajes()
            ->with('autor')
            ->oldest()
            ->get()
            ->map(fn ($m) => $this->formatear($m))
            ->values();

        return response()->json([
            'estado' => $conv->estado,
            'conversacion_id' => $conv->id,
            'mensajes' => $mensajes,
        ]);
    }

    /**
     * Estructura un mensaje para el cliente del chat.
     */
    private function formatear(Mensaje $mensaje): array
    {
        return [
            'id' => $mensaje->id,
            'autor' => $mensaje->autor?->name ?? 'Sistema',
            'es_del_aprendiz' => $mensaje->autor?->esAprendiz() ?? false,
            'propio' => auth()->id() === $mensaje->user_id,
            'hora' => $mensaje->created_at?->format('H:i') ?? '',
            'contenido' => $mensaje->contenido,
        ];
    }

    /**
     * Notifica al aprendiz dueño de la solicitud (campana + correo) sin
     * interrumpir el chat: si el envío del correo falla, se registra y
     * la atención continúa con normalidad.
     */
    private function notificarAprendiz(Solicitud $solicitud, string $titulo, string $contenido, string $tipo): void
    {
        $aprendiz = $solicitud->user;

        if (! $aprendiz || $aprendiz->esAdmin()) {
            return;
        }

        try {
            $aprendiz->notify(new NuevoMensajeDeBienestar($solicitud, $titulo, $contenido, $tipo));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}