<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios (solo activos).
     */
    public function index()
    {
        $usuarios = Usuario::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => ['required', 'string', 'max:100'],
            'nickname'   => ['required', 'string', 'max:50', 'unique:usuarios,nickname'],
            'rol'        => ['required', Rule::in(['jugador', 'admin'])],
            'contrasena' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'contrasena.confirmed' => __('Passwords do not match.'),
        ]);

        $data['contrasena'] = bcrypt($data['contrasena']);

        Usuario::create($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', __('User created successfully.'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Usuario $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'nombre'     => ['required', 'string', 'max:100'],
            'nickname'   => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuarios', 'nickname')->ignore($usuario->id, 'id'),
            ],
            'rol'        => ['required', Rule::in(['jugador', 'admin'])],
            'contrasena' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'contrasena.confirmed' => __('Passwords do not match.'),
        ]);

        if (!empty($data['contrasena'])) {
            $data['contrasena'] = bcrypt($data['contrasena']);
        } else {
            unset($data['contrasena']);
        }

        $usuario->update($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', __('User updated successfully.'));
    }

    /**
     * Desactivar usuario (soft delete).
     */
    public function destroy(Usuario $usuario)
    {
        if (Auth::id() === $usuario->id) {
            return back()->with('error', __('You cannot delete your own user.'));
        }

        // Soft delete manual + renombrar nickname
        $usuario->update([
            'deleted_at' => now(),
            'nickname'   => $usuario->nickname . '_deleted_' . $usuario->id,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', __('User deactivated successfully.'));
    }
}
