<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class LobbyController extends Controller
{
    public function index(Request $request)
    {
        $jugadores = session('partida.jugadores', []);
        return view('lobby.index', compact('jugadores'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'identificador' => ['required', 'string'],
        ], [
            'identificador.required' => __('lobby.enter_id_or_username'),
        ]);

        $ident = trim($request->input('identificador'));

        $user = ctype_digit($ident)
            ? Usuario::where('id', (int) $ident)->whereNull('deleted_at')->first()
            : Usuario::where('nickname', $ident)->whereNull('deleted_at')->first();

        if (!$user) {
            return back()->withErrors([
                'identificador' => __('lobby.user_not_found'),
            ])->withInput();
        }

        $jugadores = session('partida.jugadores', []);

        if (count($jugadores) >= 6) {
            return back()->withErrors([
                'identificador' => __('lobby.max_players'),
            ]);
        }

        foreach ($jugadores as $j) {
            if ((int) $j['id'] === (int) $user->id) {
                return back()->withErrors([
                    'identificador' => __('lobby.player_already_added'),
                ]);
            }
        }

        $jugadores[] = [
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'nombre'   => $user->nombre,
            'rol'      => $user->rol,
        ];

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('lobby')->with('ok', __('lobby.player_added'));
    }

    public function remove(Request $request, int $id)
    {
        $jugadores = array_values(array_filter(
            session('partida.jugadores', []),
            fn($j) => (int) $j['id'] !== (int) $id
        ));

        session(['partida.jugadores' => $jugadores]);

        return redirect()->route('lobby')->with('ok', __('lobby.player_removed'));
    }

    public function clear(Request $request)
    {
        session()->forget('partida.jugadores');
        return redirect()->route('lobby')->with('ok', __('lobby.list_cleared'));
    }
}
