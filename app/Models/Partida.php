<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';
    protected $primaryKey = 'id'; // ✅ tu PK real
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado',
        'ronda',
        'turno',
        'dado_restriccion',
        'creador_id',
        'creado_en',
    ];

    // Relaciones
    public function jugadores()
    {
        return $this->hasMany(PartidaJugador::class, 'partida_id', 'id')
                    ->orderBy('orden_mesa');
    }

    public function colocaciones()
    {
        return $this->hasMany(Colocacion::class, 'partida_id', 'id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creador_id', 'id');
    }
}
