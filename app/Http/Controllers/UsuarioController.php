<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // ✅ Importar Auth

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('id_usuario', 'desc')->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => ['required','string','max:100'],
            'usuario'    => ['required','string','max:50','unique:usuarios,usuario'],
            'rol'        => ['required', Rule::in(['jugador','admin'])],
            'contrasena' => ['required','string','min:6','confirmed'], // ✅ confirmada
        ], [
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $data['contrasena'] = bcrypt($data['contrasena']);
        Usuario::create($data);

        return redirect()->route('admin.usuarios.index')->with('ok','Usuario creado');
    }

    public function edit(Usuario $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'nombre'     => ['required','string','max:100'],
            'usuario'    => ['required','string','max:50','unique:usuarios,usuario,'.$usuario->id_usuario.',id_usuario'],
            'rol'        => ['required', Rule::in(['jugador','admin'])],
            'contrasena' => ['nullable','string','min:6','confirmed'], // ✅ confirmada si se ingresa
        ], [
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if (!empty($data['contrasena'])) {
            $data['contrasena'] = bcrypt($data['contrasena']);
        } else {
            unset($data['contrasena']);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')->with('ok','Usuario actualizado');
    }

    public function destroy(Usuario $usuario)
    {
        if (Auth::id() === $usuario->id_usuario) {
            return back()->with('error','No podés eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario eliminado');
    }
}
