<?php

namespace App\Notifications;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NuevoMensajeDeBienestar extends Notification
{
    use Queueable;

    /**
     * @param  string  $titulo   Encabezado mostrado en la campana y el correo.
     * @param  string  $contenido  Texto del mensaje o citación.
     * @param  string  $tipo      'mensaje' o 'citacion'.
     */
    public function __construct(
        public Solicitud $solicitud,
        public string $titulo,
        public string $contenido,
        public string $tipo = 'mensaje',
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo mensaje de Bienestar al Aprendiz')
            ->greeting('Hola, ' . ($this->solicitud->nombre_aprendiz ?: 'aprendiz') . ':')
            ->line($this->titulo . '.')
            ->when($this->contenido !== '', fn (MailMessage $m) => $m->line('"' . Str::limit($this->contenido, 160) . '"'))
            ->line('Tienes un nuevo mensaje/citación de Bienestar al Aprendiz. Haz clic aquí para ver la respuesta.')
            ->action('Ver respuesta', $this->urlChat())
            ->line('Si no esperabas este correo, puedes ignorarlo.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'solicitud_id' => $this->solicitud->id,
            'tipo' => $this->tipo,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
        ];
    }

    private function urlChat(): string
    {
        return url(route('portal.solicitudes.show', $this->solicitud));
    }
}