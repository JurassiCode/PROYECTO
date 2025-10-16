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
        'orden_mesa',
        'puntos_totales',
    ];

    /** Relaciones **/

    public function partida()
    {
        // apunta correctamente a id despues de nueva bd creo
        return $this->belongsTo(Partida::class, 'partida_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
