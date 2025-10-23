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
                'id'     => $row->usuario->id,
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


            'dinosaurios' => \App\Models\DinosaurioCatalogo::all(),
            'recintos'    => \App\Models\Recinto::all(),
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


    public function agregarColocacion(Request $request, Partida $partida)
    {
        $data = $request->validate([
            'jugador' => 'required',
            'dino' => 'required',
            'recinto' => 'required',
        ]);
    
        // 1️⃣ Buscar objetos reales
        $jugador = \App\Models\Usuario::find($data['jugador']);
        $dino = \App\Models\DinosaurioCatalogo::find($data['dino']);
        $recinto = \App\Models\Recinto::find($data['recinto']);
    
        if (!$jugador || !$dino || !$recinto) {
            return back()->withErrors(['Datos inválidos.']);
        }
    
        // 2️⃣ Calcular puntos según recinto / dino
        $puntos = 1;
        if (str_contains(strtolower($recinto->descripcion), 'bosque')) {
            $puntos = 3;
        } elseif (str_contains(strtolower($recinto->descripcion), 'montaña')) {
            $puntos = 4;
        } elseif (str_contains(strtolower($recinto->descripcion), 'río')) {
            $puntos = 2;
        } elseif (str_contains(strtolower($dino->nombre_corto), 'rex')) {
            $puntos = 5;
        }
    
        // 3️⃣ Estado actual de los jugadores (en mano / colocados)
        $jugadoresEstado = session('jugadores_estado', []);
        if (!isset($jugadoresEstado[$jugador->id])) {
            $jugadoresEstado[$jugador->id] = ['hand' => 6, 'placed' => 0];
        }
    
        // Actualizar estado
        $jugadoresEstado[$jugador->id]['hand'] = max(0, $jugadoresEstado[$jugador->id]['hand'] - 1);
        $jugadoresEstado[$jugador->id]['placed'] += 1;
    
        session(['jugadores_estado' => $jugadoresEstado]);
    
        // 4️⃣ Guardar solo la versión “formateada” de la colocación
        $colocaciones = session('colocaciones', []);
        $colocaciones[] = [
            'jugador' => $jugador->nombre,
            'dino'    => $dino->nombre_corto,
            'recinto' => $recinto->descripcion,
            'puntos'  => $puntos,
        ];
        session(['colocaciones' => $colocaciones]);
    
        // 5️⃣ Revisar si todos los jugadores ya colocaron → pasar de turno/ronda
        $totalJugadores = count($partida->jugadores);
        $colocacionesTurno = count($colocaciones);
        if ($colocacionesTurno >= $totalJugadores) {
            // Avanza turno
            $partida->turno++;
            if ($partida->turno > 6) {
                $partida->turno = 1;
                $partida->ronda++;
            }
    
            $partida->dado_restriccion = null;
            $partida->save();
    
            // Reiniciar colocaciones para el nuevo turno
            session()->forget('colocaciones');
    
            // Reset “en mano” a 6 para todos los jugadores al pasar de ronda
            if ($partida->turno === 1) {
                foreach ($jugadoresEstado as &$estado) {
                    $estado['hand'] = 6;
                    $estado['placed'] = 0;
                }
                session(['jugadores_estado' => $jugadoresEstado]);
            }
    
            return back()->with('ok', "🌿 Todos jugaron: pasa al Turno {$partida->turno} (Ronda {$partida->ronda})");
        }
    
        return back()->with('ok', "🦕 {$jugador->nombre} colocó un {$dino->nombre_corto} (+{$puntos} pts)");
    }
    
    

    public function tirarDado(Partida $partida)
{
    // Si ya hay restricción activa, no dejar tirarlo de nuevo
    if (!empty($partida->dado_restriccion)) {
        return back()->withErrors(['general' => 'El dado ya fue lanzado en este turno.']);
    }

    $opciones = [
        ['titulo' => 'Zona Izquierda', 'desc' => 'Colocar en la mitad izquierda del parque.'],
        ['titulo' => 'Zona Derecha', 'desc' => 'Colocar en la mitad derecha del parque.'],
        ['titulo' => 'Zona Boscosa', 'desc' => 'Colocar en un recinto tipo bosque o pradera.'],
        ['titulo' => 'Zona Rocosa', 'desc' => 'Colocar en un recinto de montaña o solitaria.'],
        ['titulo' => 'Recinto Vacío', 'desc' => 'Debe colocarse en un recinto sin dinosaurios.'],
        ['titulo' => 'Sin T-Rex', 'desc' => 'Debe colocarse en un recinto sin T-Rex.'],
    ];

    $random = $opciones[array_rand($opciones)];
    $partida->dado_restriccion = $random['titulo'];
    $partida->save();

    session(['restriccion' => $random]);

    return back()->with('ok', '🎲 Dado lanzado: ' . $random['titulo']);
}

}
