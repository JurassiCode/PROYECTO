<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class LobbyController extends Controller
{
    /**
     * Muestra el lobby de configuración de partida.
     * Los jugadores se cargan desde la sesión.
     */
    public function index(Request $request)
    {
        $jugadores = session('partida.jugadores', []);
        return view('lobby.index', compact('jugadores'));
    }

    /**
     * Agrega un jugador al lobby (por ID o nickname).
     */
    public function add(Request $request)
    {
        $request->validate([
            'identificador' => ['required', 'string'],
        ], [
            'identificador.required' => 'Ingresá un ID o un nombre de usuario.',
        ]);

        $ident = trim($request->input('identificador'));

        // Buscar por ID numérico o nickname
        $user = ctype_digit($ident)
            ? Usuario::where('id', (int) $ident)->whereNull('deleted_at')->first()
            : Usuario::where('nickname', $ident)->whereNull('deleted_at')->first();

        if (!$user) {
            return back()->withErrors([
                'identificador' => 'No se encontró un usuario activo con ese ID o nickname.',
            ])->withInput();
        }

        $jugadores = session('partida.jugadores', []);

        // Límite máximo de 6 jugadores
        if (count($jugadores) >= 6) {
            return back()->withErrors([
                'identificador' => 'La partida ya tiene 6 jugadores (máximo).',
            ]);
        }

        // Evitar duplicados
        foreach ($jugadores as $j) {
            if ((int) $j['id'] === (int) $user->id) {
                return back()->withErrors([
                    'identificador' => 'Ese jugador ya está en la lista.',
                ]);
            }
        }

        // Agregar jugador a la sesión
        $jugadores[] = [
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'nombre'   => $user->nombre,
            'rol'      => $user->rol,
        ];

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('lobby')->with('ok', 'Jugador agregado al lobby.');
    }

    /**
     * Quita un jugador del lobby.
     */
    public function remove(Request $request, int $id)
    {
        $jugadores = array_values(array_filter(
            session('partida.jugadores', []),
            fn($j) => (int) $j['id'] !== (int) $id
        ));

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('lobby')->with('ok', 'Jugador eliminado del lobby.');
    }

    /**
     * Vacía completamente la lista del lobby.
     */
    public function clear(Request $request)
    {
        session()->forget('partida.jugadores');
        return redirect()->route('lobby')->with('ok', 'Se vació la lista de jugadores.');
    }
}
