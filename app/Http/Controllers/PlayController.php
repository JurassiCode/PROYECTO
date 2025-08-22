<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class PlayController extends Controller
{
    // Constructor innecesario porque las rutas ya están bajo ->middleware('auth')
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $jugadores = session('partida.jugadores', []);
        return view('player.play', compact('jugadores'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'identificador' => ['required', 'string'],
        ], [
            'identificador.required' => 'Ingresá un ID o un nombre de usuario.',
        ]);

        $ident = trim($request->input('identificador'));

        // Buscar por id_usuario (numérico) o por usuario (string)
        if (ctype_digit($ident)) {
            $user = Usuario::where('id_usuario', (int)$ident)
                ->whereNull('deleted_at') // ✅
                ->first();
        } else {
            $user = Usuario::where('usuario', $ident)
                ->whereNull('deleted_at') // ✅
                ->first();
        }

        if (!$user) {
            return back()->withErrors([
                'identificador' => 'No se encontró un usuario activo con ese ID o nombre.',
            ])->withInput();
        }

        $jugadores = session('partida.jugadores', []);

        // Máximo 6
        if (count($jugadores) >= 6) {
            return back()->withErrors([
                'identificador' => 'La partida ya tiene 6 jugadores (máximo).',
            ]);
        }

        // Evitar duplicados por id_usuario
        foreach ($jugadores as $j) {
            if ((int)$j['id_usuario'] === (int)$user->id_usuario) {
                return back()->withErrors([
                    'identificador' => 'Ese jugador ya está en la partida.',
                ]);
            }
        }

        // Guardar en sesión
        $jugadores[] = [
            'id_usuario' => $user->id_usuario,
            'usuario'    => $user->usuario,
            'nombre'     => $user->nombre,
            'rol'        => $user->rol,
        ];

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('play')->with('ok', 'Jugador agregado.');
    }

    public function remove(Request $request, int $id)
    {
        $jugadores = array_values(array_filter(
            session('partida.jugadores', []),
            fn($j) => (int)$j['id_usuario'] !== (int)$id
        ));

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('play')->with('ok', 'Jugador eliminado.');
    }

    public function clear(Request $request)
    {
        session()->forget('partida.jugadores');
        return redirect()->route('play')->with('ok', 'Se vació la lista de jugadores.');
    }
}
