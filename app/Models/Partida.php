<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';
    protected $primaryKey = 'id_partida';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado',
        'creador_id',
        'creado_en'
    ];

    public function jugadores()
    {
        return $this->hasMany(PartidaJugador::class, 'partida_id', 'id_partida')
            ->orderBy('orden_mesa');
    }

    public function colocaciones()
    {
        return $this->hasMany(Colocacion::class, 'partida_id', 'id_partida');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creador_id', 'id');
    }
}
