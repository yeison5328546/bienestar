<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversacion extends Model
{
    use HasFactory;

    protected $table = 'conversaciones';

    public const ESTADO_SOLICITADA = 'solicitada';
    public const ESTADO_ACTIVA = 'activa';
    public const ESTADO_CERRADA = 'cerrada';

    public const ESTADOS = [self::ESTADO_SOLICITADA, self::ESTADO_ACTIVA, self::ESTADO_CERRADA];

    protected $fillable = [
        'solicitud_id',
        'iniciada_por',
        'estado',
        'cerrada_por',
        'iniciada_at',
        'cerrada_at',
    ];

    protected function casts(): array
    {
        return [
            'iniciada_at' => 'datetime',
            'cerrada_at' => 'datetime',
        ];
    }

    /**
     * Solicitud a la que pertenece este chat.
     */
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    /**
     * Historial de mensajes de la conversación.
     */
    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class);
    }

    /**
     * El chat está pendiente de que Natalia lo acepte.
     */
    public function esPendiente(): bool
    {
        return $this->estado === self::ESTADO_SOLICITADA;
    }

    /**
     * El chat está en curso.
     */
    public function esActiva(): bool
    {
        return $this->estado === self::ESTADO_ACTIVA;
    }

    /**
     * El chat fue cerrado por el administrador (solo lectura).
     */
    public function esCerrada(): bool
    {
        return $this->estado === self::ESTADO_CERRADA;
    }
}