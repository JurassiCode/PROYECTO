<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecintosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recintos')->truncate();
        DB::table('recintos')->insert([
            ['clave' => 'semejanza', 'descripcion' => 'Solo misma especie, llenar izq→der'],
            ['clave' => 'diferencia', 'descripcion' => 'Todas distintas, izq→der'],
            ['clave' => 'amor', 'descripcion' => 'Parejas: 5 pts c/u'],
            ['clave' => 'trio', 'descripcion' => 'Exactamente 3 dinos = 7 pts'],
            ['clave' => 'rey', 'descripcion' => '1 dino; liderar especie = 7 pts'],
            ['clave' => 'isla', 'descripcion' => '1 dino; único en el parque = 7 pts'],
            ['clave' => 'rio', 'descripcion' => 'Descarte/penalización'],
        ]);
    }
}
