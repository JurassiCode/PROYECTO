<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartidaJugador extends Model
{
    protected $table = 'partida_jugadores';
    public $timestamps = false;

    protected $fillable = ['partida_id','usuario_id','orden_mesa','puntos_totales','creado_en'];

    public function partida() {
        return $this->belongsTo(Partida::class, 'partida_id', 'id_partida');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }
}
