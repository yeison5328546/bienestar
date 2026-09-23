<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $casts = [
        'eliminacion_solicitada_at' => 'datetime',
        'eliminacion_decidida_at' => 'datetime',
    ];

    public const VENTANA_EDICION_MINUTOS = 15;

    protected $fillable = [
        'user_id',
        'nombre_aprendiz',
        'documento',
        'email',
        'telefono',
        'programa',
        'ficha',
        'tipo_requerimiento',
        'descripcion',
        'estado',
        'prioridad',
        'etiqueta',
        'recomendaciones',
        'motivacion',
        'gestion_admin',
        'alerta',
        'analizado',
        'eliminacion_estado',
        'eliminacion_motivo',
        'eliminacion_solicitada_at',
        'eliminacion_decidida_at',
    ];

    public const TIPOS = ['Apoyo psicológico', 'Depresión', 'Ansiedad y estrés', 'Problemas familiares', 'Problemas personales/emocionales', 'Acoso o discriminación', 'Prevención de riesgo', 'Otro'];

    public const ESTADOS = ['Pendiente', 'En Proceso', 'Resuelto'];

    public const PRIORIDADES = ['Baja', 'Media', 'Alta', 'Crítica'];

    public const ELIMINACION_SOLICITADA = 'solicitada';

    public const ELIMINACION_RECHAZADA = 'rechazada';

    public const ELIMINACION_ESTADOS = [self::ELIMINACION_SOLICITADA, self::ELIMINACION_RECHAZADA];

    /**
     * Aprendiz que creó la solicitud.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Historial de chats / citaciones vinculados a esta solicitud.
     */
    public function conversaciones(): HasMany
    {
        return $this->hasMany(Conversacion::class);
    }

    /**
     * Última conversación (chat) registrada para esta solicitud.
     */
    public function ultimaConversacion(): ?Conversacion
    {
        return $this->conversaciones()->latest()->first();
    }

    /**
     * Límite de la ventana de edición del aprendiz: 15 minutos desde su creación.
     */
    public function limiteEdicion(): ?Carbon
    {
        return $this->created_at?->copy()->addMinutes(self::VENTANA_EDICION_MINUTOS);
    }

    /**
     * El aprendiz dueño de la solicitud aún puede editarla.
     */
    public function edicionDisponibleParaAprendiz(): bool
    {
        $limite = $this->limiteEdicion();

        return $this->user_id !== null && $limite !== null && $limite->isFuture();
    }

    /**
     * Minutos restantes (redondeando hacia arriba) de la ventana de edición.
     */
    public function minutosRestantesDeEdicion(): int
    {
        $limite = $this->limiteEdicion();

        if (! $limite || $limite->isPast()) {
            return 0;
        }

        return max(1, (int) ceil($limite->diffInSeconds(now()) / 60));
    }

    /**
     * Texto humanizado del tiempo restante de edición, o null si ya venció.
     */
    public function textoVentanaEdicion(): ?string
    {
        if (! $this->edicionDisponibleParaAprendiz()) {
            return null;
        }

        $seg = now()->diffInSeconds($this->limiteEdicion());

        if ($seg < 60) {
            return 'menos de 1 minuto';
        }

        return $this->minutosRestantesDeEdicion().' minutos';
    }

    /**
     * El caso fue señalado por la IA como de atención prioritaria.
     */
    public function esPrioritaria(): bool
    {
        return $this->alerta || in_array($this->prioridad, ['Alta', 'Crítica'], true);
    }

    /**
     * El aprendiz aún puede enviar la petición de eliminación (no hay una previa).
     */
    public function puedeSolicitarEliminacion(): bool
    {
        return $this->eliminacion_estado === null;
    }

    /**
     * La petición de eliminación está en espera de decisión de Bienestar.
     */
    public function peticionEliminacionPendiente(): bool
    {
        return $this->eliminacion_estado === self::ELIMINACION_SOLICITADA;
    }

    /**
     * Bienestar rechazó la petición de eliminación del aprendiz.
     */
    public function peticionEliminacionRechazada(): bool
    {
        return $this->eliminacion_estado === self::ELIMINACION_RECHAZADA;
    }
}