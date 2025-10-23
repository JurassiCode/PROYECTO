<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\DinosaurioCatalogo;
use App\Models\Recinto;

class TrackeoPartidaController extends Controller
{
    public function index(Request $request)
    {
        // Jugadores cargados desde /play (sesión)
        $roster = collect(session('partida.jugadores', []))
            ->take(6)
            ->values()
            ->all();

        $palette = ['emerald','sky','purple','rose','amber','teal'];

        $jugadores = [];
        foreach ($roster as $i => $u) {
            $jugadores[] = [
                'id'      => $u['id'] ?? $i + 1,
                'nombre'  => $u['nombre'] ?? $u['usuario'] ?? 'Jugador '.($i + 1),
                'estado'  => 'Listo',
                'hand'    => 6,
                'placed'  => 0,
                'score'   => 0,
                'color'   => $palette[$i % count($palette)],
            ];
        }

        $datos = [
            'sala'       => 'ABCD-1234',
            'fase'       => 'Draft',
            'turno'      => [1, 6],
            'ronda'      => [1, 2],
            'restric'    => session('restriccion', ['titulo' => '—', 'desc' => '—']),
            'jugadores'  => $jugadores,
            'dinosaurios' => DinosaurioCatalogo::all(),
            'recintos'    => Recinto::all(),
            'score_rows'  => collect($jugadores)->map(fn($p) => [
                'jugador'  => $p['nombre'],
                'recintos' => 0,
                'parejas'  => 0,
                'trex'     => 0,
                'rio'      => 0,
                'total'    => 0,
            ])->all(),
        ];

        return view('trackeo.partida', compact('datos'));
    }

   

   
}
