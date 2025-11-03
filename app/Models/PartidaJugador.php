<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartidaJugador extends Model
{
    protected $table = 'partida_jugadores';
    public $timestamps = false;

    protected $fillable = [
        'partida_id',
        'usuario_id',
        'puntos_totales',
    ];

    /* 
     |  RELACIONES PRINCIPALES
     */

    /**
     * Partida a la que pertenece el jugador.
     * 
     * Cada fila representa la participación de un usuario dentro
     * de una partida específica.
     */
    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id', 'id');
    }

    /**
     * Usuario que participa en la partida.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
