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
    // POST /partidas
    public function store(Request $request)
    {
        $jug = session('partida.jugadores', []);
        if (empty($jug)) {
            return back()->withErrors(['general' => 'No hay jugadores cargados en /play.'])->withInput();
        }
        if (count($jug) > 6) {
            return back()->withErrors(['general' => 'Máximo 6 jugadores.'])->withInput();
        }

        $request->validate([
            'nombre' => ['required','string','max:120'],
        ], [
            'nombre.required' => 'Poné un nombre para la partida.',
        ]);

        $user = Auth::user();      // devuelve instancia de Usuario
        $userId = Auth::id();      // devuelve id_usuario (PK)
        $partida = DB::transaction(function () use ($request, $jug, $user) {
            $p = Partida::create([
                'nombre'     => $request->nombre,
                'estado'     => 'config',
                'ronda'      => 1,
                'turno'      => 1,
                'creador_id' => $user->id_usuario,
            ]);

            foreach (array_values($jug) as $i => $j) {
                if (!Usuario::where('id_usuario', $j['id_usuario'])->exists()) continue;

                PartidaJugador::create([
                    'partida_id'     => $p->id_partida,
                    'usuario_id'     => $j['id_usuario'],
                    'orden_mesa'     => $i + 1,
                    'puntos_totales' => 0,
                ]);
            }

            return $p;
        });

        return redirect()->route('trackeo.partida', $partida->id_partida)
            ->with('ok', 'Partida creada.');
    }

    // GET /trackeo-partida/{partida}
    public function show(Partida $partida)
    {
        $pj = $partida->jugadores()->with('usuario')->get();

        $palette = ['emerald','sky','purple','rose','amber','teal'];
        $jugadores = [];
        foreach ($pj as $i => $row) {
            $jugadores[] = [
                'nombre' => $row->usuario->nombre ?: $row->usuario->usuario,
                'estado' => 'Listo',
                'hand'   => 6,
                'placed' => 0,
                'score'  => (int)$row->puntos_totales,
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

        return view('trackeo.partida', compact('datos'));
    }

    
}
