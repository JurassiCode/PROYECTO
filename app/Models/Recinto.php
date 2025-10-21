<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recinto extends Model
{
    protected $table = 'recintos_catalogo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['clave','descripcion'];
}
