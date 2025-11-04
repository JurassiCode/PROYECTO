<?php

namespace App\Http\Controllers;

use App\Models\Partida;

class ResultadosController extends Controller
{
    public function show(Partida $partida)
    {
        //  Asegurarse de que la partida esté cerrada (opcional)
        if ($partida->estado !== 'cerrada') {
            return redirect()
                ->route('trackeo.partida.show', $partida->id)
                ->with('info', __('The game has not been closed yet.'));
        }

        //  Obtener jugadores con puntajes reales
        $jugadores = $partida->jugadores()
            ->with('usuario')
            ->orderByDesc('puntos_totales')
            ->get()
            ->map(fn($pj) => [
                'nombre' => $pj->usuario->nombre ?? $pj->usuario->nickname,
                'puntos' => $pj->puntos_totales,
            ])
            ->toArray();

        //  Renderizar la misma vista de cierre
        return view('trackeo.resultados', [
            'partida'   => $partida,
            'jugadores' => $jugadores,
        ]);
    }
}
