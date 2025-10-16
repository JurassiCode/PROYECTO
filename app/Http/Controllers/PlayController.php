<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class PlayController extends Controller
{
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

        // Buscar por ID numérico o por nickname (coincide con la BD actual)
        if (ctype_digit($ident)) {
            $user = Usuario::where('id', (int) $ident)
                ->whereNull('deleted_at')
                ->first();
        } else {
            $user = Usuario::where('nickname', $ident)
                ->whereNull('deleted_at')
                ->first();
        }

        if (!$user) {
            return back()->withErrors([
                'identificador' => 'No se encontró un usuario activo con ese ID o nickname.',
            ])->withInput();
        }

        $jugadores = session('partida.jugadores', []);

        // Máximo 6 jugadores
        if (count($jugadores) >= 6) {
            return back()->withErrors([
                'identificador' => 'La partida ya tiene 6 jugadores (máximo).',
            ]);
        }

        // Evitar duplicados por ID
        foreach ($jugadores as $j) {
            if ((int) $j['id'] === (int) $user->id) {
                return back()->withErrors([
                    'identificador' => 'Ese jugador ya está en la partida.',
                ]);
            }
        }

        // Guardar en sesión (con claves coherentes con la vista)
        $jugadores[] = [
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'nombre'   => $user->nombre,
            'rol'      => $user->rol,
        ];

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('play')->with('ok', 'Jugador agregado.');
    }

    public function remove(Request $request, int $id)
    {
        $jugadores = array_values(array_filter(
            session('partida.jugadores', []),
            fn($j) => (int) $j['id'] !== (int) $id
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
