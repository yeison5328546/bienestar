<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_ADMIN = 'admin';

    public const ROL_APRENDIZ = 'aprendiz';

    public const ROLES = [self::ROL_ADMIN, self::ROL_APRENDIZ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'documento',
        'programa',
        'ficha',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Es el administrador único (Natalia) del sistema.
     */
    public function esAdmin(): bool
    {
        return $this->role === self::ROL_ADMIN;
    }

    /**
     * El usuario cuenta con el rol de Aprendiz.
     */
    public function esAprendiz(): bool
    {
        return $this->role === self::ROL_APRENDIZ;
    }

    /**
     * Solicitudes creadas por este aprendiz.
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'user_id');
    }
}