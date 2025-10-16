<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\PartidaJugador;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PartidasController extends Controller
{
    /**
     * Crea una nueva partida y asocia los jugadores cargados en sesión (/play)
     */
    public function store(Request $request)
    {
        $jugadores = session('partida.jugadores', []);

        if (empty($jugadores)) {
            return back()->withErrors(['general' => 'No hay jugadores cargados en /play.'])->withInput();
        }

        if (count($jugadores) > 6) {
            return back()->withErrors(['general' => 'Máximo 6 jugadores.'])->withInput();
        }

        $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
        ], [
            'nombre.required' => 'Poné un nombre para la partida.',
        ]);

        $user = Auth::user();

        $partida = DB::transaction(function () use ($request, $jugadores, $user) {
            // crear partida con id correcto
            $p = Partida::create([
                'nombre'      => $request->nombre,
                'estado'      => 'config',
                'ronda'       => 1,
                'turno'       => 1,
                'creador_id'  => $user->id, 
            ]);

            // Insertar jugadores asociados
            foreach (array_values($jugadores) as $i => $j) {
                if (!Usuario::where('id', $j['id'])->exists()) continue;

                PartidaJugador::create([
                    'partida_id'     => $p->id,
                    'usuario_id'     => $j['id'],
                    'orden_mesa'     => $i + 1,
                    'puntos_totales' => 0,
                ]);
            }

            return $p;
        });

        // Limpiar sesión luego de crear la partida
        session()->forget('partida.jugadores');

        return redirect()
            ->route('trackeo.partida.show', $partida)
            ->with('ok', 'Partida creada correctamente.');
    }

    /**
     * Muestra una partida activa (pantalla de trackeo)
     */
    public function show(Partida $partida)
    {
        $pj = $partida->jugadores()->with('usuario')->get();

        $palette = ['emerald', 'sky', 'purple', 'rose', 'amber', 'teal'];
        $jugadores = [];
        foreach ($pj as $i => $row) {
            $jugadores[] = [
                'nombre' => $row->usuario->nombre ?: $row->usuario->nickname,
                'estado' => 'Listo',
                'hand'   => 6,
                'placed' => 0,
                'score'  => (int) $row->puntos_totales,
                'color'  => $palette[$i % count($palette)],
            ];
        }

        $datos = [
            'sala'    => $partida->nombre,
            'fase'    => 'Draft',
            'turno'   => [$partida->turno, 6],
            'ronda'   => [$partida->ronda, 2],
            'restric' => [
                'titulo' => $partida->dado_restriccion ?? '—',
                'desc'   => '—',
                'tags'   => [],
            ],
            'jugadores'  => $jugadores,
            'score_rows' => collect($jugadores)->map(fn($p) => [
                'jugador'  => $p['nombre'],
                'recintos' => 0,
                'parejas'  => 0,
                'trex'     => 0,
                'rio'      => 0,
                'total'    => $p['score'],
            ])->all(),
        ];

        return view('trackeo.partida', [
            'datos'   => $datos,
            'partida' => $partida,
        ]);
    }

    /**
     * Finaliza una partida (placeholder)
     */
    public function finalizar(Partida $partida)
    {
        $partida->estado = 'cerrada';
        $partida->save();

        return redirect()
            ->route('resultados.partida.show', $partida->id)
            ->with('ok', "La partida #{$partida->id} fue finalizada.");
    }
}
