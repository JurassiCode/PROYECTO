<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';
    protected $primaryKey = 'id'; // PK real de la tabla
    public $timestamps = false;

    // Campos que se pueden asignar masivamente (mass assignable)
    protected $fillable = [
        'nombre',
        'estado',
        'ronda',
        'turno',
        'dado_restriccion',
        'creador_id',
        'creado_en',
    ];

    /* ===============================================================
     | 🧩 RELACIONES PRINCIPALES
     |===============================================================*/

    /**
     * Jugadores asociados a esta partida.
     * 
     * Antes ordenábamos por "orden_mesa", pero ya no existe esa columna.
     * Ahora simplemente devolvemos todos los jugadores de la partida.
     */
    public function jugadores()
    {
        return $this->hasMany(PartidaJugador::class, 'partida_id', 'id');
    }

    /**
     * Colocaciones realizadas durante la partida.
     * 
     * Cada colocación representa una jugada de un jugador en un turno.
     */
    public function colocaciones()
    {
        return $this->hasMany(Colocacion::class, 'partida_id', 'id');
    }

    /**
     * Usuario creador de la partida (quien la inició desde el lobby).
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creador_id', 'id');
    }
}
