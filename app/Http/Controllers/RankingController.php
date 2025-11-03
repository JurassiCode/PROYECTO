<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\PartidaJugador;

class RankingController extends Controller
{
    public function index()
    {
        // Consulta de ranking global (jugadores con más puntos totales acumulados)
        $ranking = PartidaJugador::selectRaw('usuario_id, SUM(puntos_totales) as puntos_acumulados')
            ->groupBy('usuario_id')
            ->orderByDesc('puntos_acumulados')
            ->take(20) // Top 20 jugadores
            ->with('usuario:id,nickname')
            ->get();

        return view('ranking.index', compact('ranking'));
    }
}
