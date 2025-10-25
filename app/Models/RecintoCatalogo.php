<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecintoCatalogo extends Model
{
    protected $table = 'recintos_catalogo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['clave', 'descripcion', 'tipo_regla', 'max_dinos'];
}
