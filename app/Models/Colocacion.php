<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocacion extends Model
{
    protected $table = 'colocaciones';
    public $timestamps = false;

    protected $fillable = [
        'partida_id',
        'usuario_id',
        'ronda',
        'turno',
        'recinto_id',
        'tipo_dino',
        'pts_obtenidos',
        'creado_en',
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id', 'id');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
    public function recintoCatalogo()
    {
        return $this->belongsTo(RecintoCatalogo::class, 'recinto_id', 'id');
    }
    public function dinoCatalogo()
    {
        return $this->belongsTo(DinosaurioCatalogo::class, 'tipo_dino', 'id');
    }
}
