<?php

namespace App\Http\Controllers;

use App\Models\Partida;

class ResultadosController extends Controller
{
    public function show(Partida $partida)
    {
        // Por ahora datos truchos
        $jugadores = [
            ['nombre' => 'Nacho', 'puntos' => 120],
            ['nombre' => 'Seba',  'puntos' => 95],
            ['nombre' => 'Tomi',  'puntos' => 85],
            ['nombre' => 'Joaco', 'puntos' => 70],
        ];

        return view('trackeo.resultados', [
            'partida'   => $partida,
            'jugadores' => $jugadores,
        ]);
    }
}
