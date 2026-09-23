<?php

namespace App\Policies;

use App\Models\Solicitud;
use App\Models\User;

class SolicitudPolicy
{
    /**
     * El administrador tiene acceso total vía Gate::before.
     * Solo se evalúan aquí las reglas del aprendiz.
     */

    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * El aprendiz solo ve sus propias solicitudes.
     */
    public function view(User $user, Solicitud $solicitud): bool
    {
        return $solicitud->user_id === $user->id;
    }

    /**
     * El aprendiz puede registrar solicitudes desde su portal.
     */
    public function create(User $user): bool
    {
        return $user->esAprendiz();
    }

    /**
     * El aprendiz solo modifica sus solicitudes dentro de los 15 minutos posteriores a la creación.
     */
    public function update(User $user, Solicitud $solicitud): bool
    {
        return $solicitud->user_id === $user->id
            && $solicitud->edicionDisponibleParaAprendiz();
    }

    /**
     * El aprendiz no elimina solicitudes; es control exclusivo del administrador.
     */
    public function delete(User $user, Solicitud $solicitud): bool
    {
        return false;
    }

    /**
     * El cambio de estado queda reservado al administrador.
     */
    public function cambiarEstado(User $user, Solicitud $solicitud): bool
    {
        return false;
    }

    /**
     * El aprendiz solo solicita eliminación de una solicitud propia que aún no tenga una petición abierta.
     */
    public function solicitarEliminacion(User $user, Solicitud $solicitud): bool
    {
        return $solicitud->user_id === $user->id
            && $solicitud->puedeSolicitarEliminacion();
    }
}