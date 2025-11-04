<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\PartidaJugador;
use App\Models\Usuario;
use App\Models\Colocacion;
use App\Models\DinosaurioCatalogo;
use App\Models\RecintoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ServicioPuntaje;

class PartidasController extends Controller
{
    public function store(Request $request)
    {
        $jugadores = session('partida.jugadores', []);

        if (empty($jugadores)) {
            return back()->withErrors(['general' => __('No players loaded in the lobby.')]);
        }

        if (count($jugadores) > 6) {
            return back()->withErrors(['general' => __('Maximum 6 players per game.')]);
        }

        $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
        ], [
            'nombre.required' => __('Enter a name for the game.'),
        ]);

        $user = Auth::user();

        $partida = DB::transaction(function () use ($request, $jugadores, $user) {
            $p = Partida::create([
                'nombre'      => $request->nombre,
                'estado'      => 'en_curso',
                'ronda'       => 1,
                'turno'       => 1,
                'creador_id'  => $user->id,
            ]);

            foreach ($jugadores as $j) {
                if (!Usuario::where('id', $j['id'])->exists()) continue;

                PartidaJugador::create([
                    'partida_id'     => $p->id,
                    'usuario_id'     => $j['id'],
                    'puntos_totales' => 0,
                ]);
            }

            return $p;
        });

        session()->forget('partida.jugadores');

        return redirect()
            ->route('trackeo.partida.show', $partida)
            ->with('ok', __('Game created successfully.'));
    }

    public function show(Partida $partida)
    {
        if ($partida->estado === 'cerrada') {
            return redirect()
                ->route('resultados.partida.show', $partida->id)
                ->with('info', __('This game has already been finished.'));
        }

        $pj = $partida->jugadores()->with('usuario')->get();
        $palette = ['emerald', 'sky', 'purple', 'rose', 'amber', 'teal'];

        $jugadores = [];
        foreach ($pj as $i => $row) {
            $colocados = Colocacion::where('partida_id', $partida->id)
                ->where('usuario_id', $row->usuario->id)
                ->where('ronda', $partida->ronda)
                ->count();

            $jugadores[] = [
                'id'     => $row->usuario->id,
                'nombre' => $row->usuario->nombre ?: $row->usuario->nickname,
                'hand'   => max(6 - $colocados, 0),
                'placed' => $colocados,
                'score'  => (int) $row->puntos_totales,
                'color'  => $palette[$i % count($palette)],
            ];
        }

        $colocaciones = Colocacion::where('partida_id', $partida->id)
            ->where('ronda', $partida->ronda)
            ->where('turno', $partida->turno)
            ->with(['usuario', 'recintoCatalogo', 'dinoCatalogo'])
            ->get();

        $descripciones = [
            'El Bosque' => __('The dinosaurs must be placed in any Forest area enclosure.'),
            'Llanura' => __('The dinosaurs must be placed in any Plain area enclosure.'),
            'Baños' => __('The dinosaurs must be placed only in enclosures on the right side of the River.'),
            'Cafetería' => __('The dinosaurs must be placed only in enclosures on the left side of the River.'),
            'Recinto Vacío' => __('The dinosaurs must be placed in an empty enclosure.'),
            '¡Cuidado con el T-Rex!' => __('The dinosaurs must be placed in an enclosure that does not already contain a T-Rex.'),
        ];

        $titulo = $partida->dado_restriccion ?? '—';
        $desc   = $descripciones[$titulo] ?? __('Roll the dice to start.');

        session(['restriccion' => [
            'titulo' => $titulo,
            'desc'   => $desc,
        ]]);

        $datos = [
            'sala'    => $partida->nombre,
            'fase'    => 'Draft',
            'turno'   => [$partida->turno, 6],
            'ronda'   => [$partida->ronda, 2],
            'restric' => [
                'titulo' => $titulo,
                'desc'   => $desc,
                'tags'   => [],
            ],
            'jugadores'  => $jugadores,
            'score_rows' => collect($jugadores)->map(fn($p) => [
                'jugador'  => $p['nombre'],
                'total'    => $p['score'],
            ])->all(),
            'dinosaurios' => DinosaurioCatalogo::all(),
            'recintos'    => RecintoCatalogo::all(),
        ];

        return view('trackeo.partida', [
            'datos'         => $datos,
            'partida'       => $partida,
            'colocaciones'  => $colocaciones,
        ]);
    }

    public function agregarColocacion(Request $request, Partida $partida)
    {
        $data = $request->validate([
            'jugador' => 'required|integer',
            'dino'    => 'required|integer',
            'recinto' => 'required|integer',
        ]);

        if (empty($partida->dado_restriccion)) {
            return back()->withErrors([
                'general' => __('Cannot place a dinosaur before rolling the dice.')
            ]);
        }

        $yaColoco = Colocacion::where('partida_id', $partida->id)
            ->where('usuario_id', $data['jugador'])
            ->where('ronda', $partida->ronda)
            ->where('turno', $partida->turno)
            ->exists();

        if ($yaColoco) {
            return back()->withErrors([
                'general' => __('This player already placed a dinosaur this turn.')
            ]);
        }

        $jugador = Usuario::find($data['jugador']);
        $dino    = DinosaurioCatalogo::find($data['dino']);
        $recinto = RecintoCatalogo::find($data['recinto']);

        if (!$jugador || !$dino || !$recinto) {
            return back()->withErrors(['general' => __('Invalid or nonexistent data.')]);
        }

        $servicioPuntaje = new ServicioPuntaje();
        $resultado = $servicioPuntaje->evaluarJugada(
            $partida->dado_restriccion ?? '',
            $recinto,
            $dino,
            $partida->id,
            $jugador->id
        );

        if (!$resultado['valido']) {
            return back()->withErrors(['general' => __('Invalid move:') . ' ' . $resultado['motivo']]);
        }

        Colocacion::create([
            'partida_id'    => $partida->id,
            'usuario_id'    => $jugador->id,
            'ronda'         => $partida->ronda,
            'turno'         => $partida->turno,
            'recinto_id'    => $recinto->id,
            'tipo_dino'     => $dino->id,
            'pts_obtenidos' => $resultado['puntos'],
        ]);

        PartidaJugador::where('partida_id', $partida->id)
            ->where('usuario_id', $jugador->id)
            ->increment('puntos_totales', $resultado['puntos']);

        $totalJugadores = $partida->jugadores()->count();
        $colocacionesTurno = Colocacion::where('partida_id', $partida->id)
            ->where('ronda', $partida->ronda)
            ->where('turno', $partida->turno)
            ->count();

        if ($colocacionesTurno >= $totalJugadores) {
            $partida->turno++;
            if ($partida->turno > 6) {
                $partida->turno = 1;
                $partida->ronda++;
            }

            $partida->dado_restriccion = null;
            $partida->save();

            session()->forget(['restriccion', 'tirador_id']);

            return back()->with('ok', __('All players have played, moving to Turn :turn (Round :round)', [
                'turn' => $partida->turno,
                'round' => $partida->ronda
            ]));
        }

        return back()->with('ok', __('Player :name placed a :dino (+:points pts)', [
            'name' => $jugador->nombre,
            'dino' => $dino->nombre_corto,
            'points' => $resultado['puntos']
        ]));
    }

    public function tirarDado(Request $request, Partida $partida)
    {
        $request->validate([
            'tirador' => 'required|integer|exists:usuarios,id'
        ]);

        if (!empty($partida->dado_restriccion)) {
            return back()->withErrors(['general' => __('The dice has already been rolled this turn.')]);
        }

        $opciones = [
            ['titulo' => 'El Bosque', 'desc' => __('The dinosaurs must be placed in any Forest area enclosure.')],
            ['titulo' => 'Llanura', 'desc' => __('The dinosaurs must be placed in any Plain area enclosure.')],
            ['titulo' => 'Baños', 'desc' => __('The dinosaurs must be placed only in enclosures on the right side of the River.')],
            ['titulo' => 'Cafetería', 'desc' => __('The dinosaurs must be placed only in enclosures on the left side of the River.')],
            ['titulo' => 'Recinto Vacío', 'desc' => __('The dinosaurs must be placed in an empty enclosure.')],
            ['titulo' => '¡Cuidado con el T-Rex!', 'desc' => __('The dinosaurs must be placed in an enclosure that does not already contain a T-Rex.')],
        ];

        $random = $opciones[array_rand($opciones)];

        $partida->update(['dado_restriccion' => $random['titulo']]);
        session([
            'restriccion' => [
                'titulo' => $random['titulo'],
                'desc'   => $random['desc'],
            ],
            'tirador_id' => (int) $request->tirador,
        ]);

        return back()->with('ok', __('Dice rolled:') . ' ' . $random['titulo']);
    }

    public function finalizar(Partida $partida)
    {
        if ($partida->estado === 'cerrada') {
            return redirect()->route('resultados.partida.show', $partida->id);
        }

        $partida->update([
            'estado' => 'cerrada',
            'dado_restriccion' => null,
        ]);

        session()->forget(['restriccion', 'tirador_id']);

        return redirect()
            ->route('resultados.partida.show', $partida->id)
            ->with('ok', __('The game :name has been closed successfully.', ['name' => $partida->nombre]));
    }
}
