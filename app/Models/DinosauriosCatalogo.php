<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinosaurioCatalogo extends Model
{
    protected $table = 'dinosaurios_catalogo';
    protected $primaryKey = 'id_dino';
    public $timestamps = false;

    protected $fillable = ['nombre_corto','categoria'];
}
