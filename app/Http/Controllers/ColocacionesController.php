<?php

namespace App\Http\Controllers;

use App\Models\Colocacion;
use App\Models\Partida;
use App\Models\PartidaJugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColocacionesController extends Controller
{
    // POST /partidas/{partida}/colocaciones
    public function store(Request $request, Partida $partida)
    {
        $data = $request->validate([
            'usuario_id' => ['required','integer'],
            'ronda'      => ['required','integer','min:1','max:2'],
            'turno'      => ['required','integer','min:1','max:6'],
            'recinto_id' => ['required','integer'],
            'tipo_dino'  => ['required','integer'],
        ]);

        // Acá va tu validación/puntaje real
        $valido = true;
        $motivo = null;
        $pts    = 0;

        DB::transaction(function () use ($partida, $data, $valido, $motivo, $pts) {
            Colocacion::create([
                'partida_id'       => $partida->id_partida,
                'usuario_id'       => $data['usuario_id'],
                'ronda'            => $data['ronda'],
                'turno'            => $data['turno'],
                'recinto_id'       => $data['recinto_id'],
                'tipo_dino'        => $data['tipo_dino'],
                'valido'           => $valido,
                'motivo_invalidez' => $motivo,
                'pts_obtenidos'    => $pts,
            ]);

            if ($pts !== 0) {
                PartidaJugador::where('partida_id', $partida->id_partida)
                    ->where('usuario_id', $data['usuario_id'])
                    ->update(['puntos_totales' => DB::raw('puntos_totales + '.(int)$pts)]);
            }
        });

        return back()->with('ok', 'Jugada registrada.');
    }
}
