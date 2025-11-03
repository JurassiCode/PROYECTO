<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Partida;
use App\Models\PartidaJugador;

class PerfilController extends Controller
{
    /**
     *  Mostrar perfil del usuario logueado
     */
    public function show()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        //  Partidas creadas por el usuario
        $partidasCreadas = Partida::where('creador_id', $user->id)
            ->orderBy('creado_en', 'desc')
            ->get();

        //  Partidas donde participó
        $partidasJugadas = PartidaJugador::with('partida')
            ->where('usuario_id', $user->id)
            ->orderByDesc('id')
            ->get();

        //   Stats básicas
        $stats = [
            'jugadas' => $partidasJugadas->count(),
            'creadas' => $partidasCreadas->count(),
            'puntos_totales' => $partidasJugadas->sum('puntos_totales'),
            'ganadas' => null, // <-- prepará para futuro cálculo
        ];

        return view('perfil.index', compact('user', 'partidasCreadas', 'partidasJugadas', 'stats'));
    }

    /**
     *   Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    /**
     *  Actualizar los datos del perfil del usuario
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'nickname' => 'required|string|max:100|unique:usuarios,nickname,' . $user->id,
            'contrasena' => 'nullable|min:8|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nickname.unique' => 'Ese nombre de usuario ya está en uso.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user->nombre = $request->nombre;
        $user->nickname = $request->nickname;

        if ($request->filled('contrasena')) {
            $user->contrasena = Hash::make($request->contrasena);
        }

        $user->save();

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente ✅');
    }
}
