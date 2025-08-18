<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /** Tabla y PK personalizadas */
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $rememberTokenName = null;

    /** Campos asignables */
    protected $fillable = [
        'nombre',
        'usuario',
        'contrasena',
        'rol',
        'creado_en', // lo completa la DB por DEFAULT CURRENT_TIMESTAMP
    ];

    /** Ocultamos la contraseña al serializar */
    protected $hidden = [
        'contrasena',
    ];

    /**
     * Hace que Auth::attempt([... 'password' => ...]) compare contra "contrasena".
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['contrasena'] = $value;
    }

    public function getPasswordAttribute(): ?string
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName()
{
    return 'id_usuario';
}
}
