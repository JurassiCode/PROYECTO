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
    /* ===============================================================
     | 🦖 Crear una nueva partida (desde el lobby)
     |===============================================================*/
    public function store(Request $request)
    {
        $jugadores = session('partida.jugadores', []);

        if (empty($jugadores)) {
            return back()->withErrors(['general' => 'No hay jugadores cargados en el lobby.']);
        }

        if (count($jugadores) > 6) {
            return back()->withErrors(['general' => 'Máximo 6 jugadores por partida.']);
        }

        $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
        ], [
            'nombre.required' => 'Poné un nombre para la partida.',
        ]);

        $user = Auth::user();

        // 💾 Crear partida y asociar jugadores
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
            ->with('ok', '✅ Partida creada correctamente.');
    }

    /* ===============================================================
     | 🎮 Mostrar partida en curso (pantalla de trackeo)
     |===============================================================*/
    public function show(Partida $partida)
    {
        // 🚫 Si la partida ya fue cerrada, redirigir directamente a los resultados
        if ($partida->estado === 'cerrada') {
            return redirect()
                ->route('resultados.partida.show', $partida->id)
                ->with('info', 'Esta partida ya fue finalizada.');
        }

        // 🔹 Obtener jugadores y estado actual de la partida
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

        // 🧩 Descripciones reales según el manual del Dado
        $descripciones = [
            'El Bosque' => 'Los dinosaurios deben colocarse en cualquier recinto del área de Bosque del parque.',
            'Llanura' => 'Los dinosaurios deben colocarse en cualquier recinto del área de Llanura del parque.',
            'Baños' => 'Los dinosaurios deben colocarse únicamente en los recintos que se encuentren a la derecha del Río.',
            'Cafetería' => 'Los dinosaurios deben colocarse únicamente en los recintos que se encuentren a la izquierda del Río.',
            'Recinto Vacío' => 'Los dinosaurios deben colocarse en un recinto vacío del parque.',
            '¡Cuidado con el T-Rex!' => 'Los dinosaurios deben colocarse en un recinto que no contenga previamente un T-Rex.',
        ];

        $titulo = $partida->dado_restriccion ?? '—';
        $desc   = $descripciones[$titulo] ?? 'Tirá el dado para comenzar.';

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


    /* ===============================================================
     | 🦕 Registrar una colocación real
     |===============================================================*/
    public function agregarColocacion(Request $request, Partida $partida)
    {
        $data = $request->validate([
            'jugador' => 'required|integer',
            'dino'    => 'required|integer',
            'recinto' => 'required|integer',
        ]);

        if (empty($partida->dado_restriccion)) {
            return back()->withErrors([
                'general' => '🎲 No se puede colocar un dinosaurio antes de lanzar el dado.'
            ]);
        }

        $yaColoco = Colocacion::where('partida_id', $partida->id)
            ->where('usuario_id', $data['jugador'])
            ->where('ronda', $partida->ronda)
            ->where('turno', $partida->turno)
            ->exists();

        if ($yaColoco) {
            return back()->withErrors([
                'general' => '🦕 Este jugador ya colocó su dinosaurio en este turno.'
            ]);
        }

        $jugador = Usuario::find($data['jugador']);
        $dino    = DinosaurioCatalogo::find($data['dino']);
        $recinto = RecintoCatalogo::find($data['recinto']);

        if (!$jugador || !$dino || !$recinto) {
            return back()->withErrors(['general' => 'Datos inválidos o inexistentes.']);
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
            return back()->withErrors(['general' => '❌ Jugada inválida: ' . $resultado['motivo']]);
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

            return back()->with('ok', "🌿 Todos jugaron: pasa al Turno {$partida->turno} (Ronda {$partida->ronda})");
        }

        return back()->with('ok', "🦕 {$jugador->nombre} colocó un {$dino->nombre_corto} (+{$resultado['puntos']} pts)");
    }

    /* ===============================================================
     | 🎲 Tirar dado (real)
     |===============================================================*/
    public function tirarDado(Request $request, Partida $partida)
    {
        // 🔸 Validar jugador
        $request->validate([
            'tirador' => 'required|integer|exists:usuarios,id'
        ]);

        if (!empty($partida->dado_restriccion)) {
            return back()->withErrors(['general' => 'El dado ya fue lanzado en este turno.']);
        }

        $opciones = [
            ['titulo' => 'El Bosque', 'desc' => 'Los dinosaurios deben colocarse en cualquier recinto del área de Bosque del parque.'],
            ['titulo' => 'Llanura', 'desc' => 'Los dinosaurios deben colocarse en cualquier recinto del área de Llanura del parque.'],
            ['titulo' => 'Baños', 'desc' => 'Los dinosaurios deben colocarse únicamente en los recintos que se encuentren a la derecha del Río.'],
            ['titulo' => 'Cafetería', 'desc' => 'Los dinosaurios deben colocarse únicamente en los recintos que se encuentren a la izquierda del Río.'],
            ['titulo' => 'Recinto Vacío', 'desc' => 'Los dinosaurios deben colocarse en un recinto vacío del parque.'],
            ['titulo' => '¡Cuidado con el T-Rex!', 'desc' => 'Los dinosaurios deben colocarse en un recinto que no contenga previamente un T-Rex.'],
        ];

        $random = $opciones[array_rand($opciones)];

        // 🔹 Guardar restricción y quién tiró (en sesión)
        $partida->update(['dado_restriccion' => $random['titulo']]);
        session([
            'restriccion' => [
                'titulo' => $random['titulo'],
                'desc'   => $random['desc'],
            ],
            'tirador_id' => (int) $request->tirador, // 👈 se guarda el tirador actual
        ]);

        return back()->with('ok', '🎲 Dado lanzado: ' . $random['titulo']);
    }


    public function finalizar(Partida $partida)
    {
        // 🚫 Evitar cierre duplicado
        if ($partida->estado === 'cerrada') {
            return redirect()->route('resultados.partida.show', $partida->id);
        }

        // 🔹 Marcar como cerrada
        $partida->update([
            'estado' => 'cerrada',
            'dado_restriccion' => null,
        ]);

        // 🔹 Limpiar sesión
        session()->forget(['restriccion', 'tirador_id']);

        // 🔹 Redirigir a la ruta de resultados
        return redirect()
            ->route('resultados.partida.show', $partida->id)
            ->with('ok', "🏁 La partida '{$partida->nombre}' fue cerrada correctamente.");
    }
}
