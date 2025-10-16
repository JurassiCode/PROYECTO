<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /** Tabla y clave primaria */
    protected $table = 'usuarios';
    protected $primaryKey = 'id'; // ← tu BD usa 'id', no 'id_usuario'
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $rememberTokenName = null;

    /** Campos asignables */
    protected $fillable = [
        'nombre',
        'nickname',    
        'contrasena',
        'rol',
        'creado_en',
        'deleted_at',
    ];

    /** Ocultamos la contraseña al serializar */
    protected $hidden = [
        'contrasena',
    ];

    /**
     * Le dice a Laravel que el campo de autenticación de contraseña es "contrasena"
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Le dice a Laravel que el identificador de usuario (username) es "nickname"
     */
    public function getAuthIdentifierName()
    {
        return 'nickname';
    }
}
