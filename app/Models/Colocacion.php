<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocacion extends Model
{
    protected $table = 'colocaciones';
    public $timestamps = false;

    protected $fillable = [
        'partida_id','usuario_id','ronda','turno','recinto_id','tipo_dino',
        'valido','motivo_invalidez','pts_obtenidos','creado_en'
    ];

    public function partida() { return $this->belongsTo(Partida::class, 'partida_id', 'id_partida'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario'); }
    public function recinto() { return $this->belongsTo(Recinto::class, 'recinto_id', 'id_recinto'); }
    public function dino()    { return $this->belongsTo(DinosaurioCatalogo::class, 'tipo_dino', 'id_dino'); }
}
