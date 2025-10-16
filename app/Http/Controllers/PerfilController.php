<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

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

        return back()->with('success', 'Perfil actualizado correctamente ✅');
    }
}
