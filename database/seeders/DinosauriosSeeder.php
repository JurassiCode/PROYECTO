<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DinosauriosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dinosaurios_catalogo')->truncate();
        DB::table('dinosaurios_catalogo')->insert([
            ['nombre_corto' => 'TYRANNOSAURUS', 'categoria' => 'carnivoro'],
            ['nombre_corto' => 'BRACHIOSAURUS', 'categoria' => 'herbivoro'],
            ['nombre_corto' => 'STEGOSAURUS',   'categoria' => 'herbivoro'],
            ['nombre_corto' => 'TRICERATOPS',   'categoria' => 'herbivoro'],
            ['nombre_corto' => 'PTERODACTYL',   'categoria' => 'volador'],
            ['nombre_corto' => 'DIPLODOCUS',    'categoria' => 'herbivoro'],
        ]);
    }
}
