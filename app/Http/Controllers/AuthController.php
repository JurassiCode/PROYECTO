<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /** -------------------- LOGIN -------------------- */

    /**
     * Muestra el login si no está autenticado; si lo está, redirige según rol.
     */
    public function show(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->rol === 'admin'
                ? redirect()->route('admin.home')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login con usuario + password.
     */
    public function login(Request $request)
    {
        $cred = $request->validate(
            [
                'usuario'  => ['required', 'string'],
                'password' => ['required', 'string'],
            ],
            [
                'usuario.required'  => 'El campo usuario es obligatorio.',
                'usuario.string'    => 'El usuario debe ser un texto válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.string'   => 'La contraseña debe ser un texto válido.',
            ]
        );

        // 1) Buscar usuario por nombre
        $usuario = Usuario::where('usuario', $cred['usuario'])->first();

        if (!$usuario) {
            return back()
                ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
                ->onlyInput('usuario');
        }

        // 2) Verificar si está desactivado
        if ($usuario->deleted_at !== null) {
            return back()
                ->withErrors(['usuario' => 'Este usuario ha sido desactivado.'])
                ->onlyInput('usuario');
        }

        // 3) Intentar login con Auth::attempt
        if (Auth::attempt(['usuario' => $cred['usuario'], 'password' => $cred['password']], false)) {
            $request->session()->regenerate();

            // Soportar "next" solo si es ruta interna (evitar open redirect)
            if ($request->filled('next') && Str::startsWith($request->next, '/')) {
                return redirect($request->next);
            }

            // Si venía de /admin y es admin → admin.home
            $prevUrl = url()->previous();
            if (str_contains($prevUrl, '/admin') && Auth::user()->rol === 'admin') {
                return redirect()->route('admin.home');
            }

            // Por defecto → home
            return redirect()->route('home');
        }

        return back()
            ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
            ->onlyInput('usuario');
    }

    /**
     * Cierra sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /** ------------------ REGISTER ------------------- */

    /**
     * Muestra el formulario de registro si no está autenticado.
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->rol === 'admin'
                ? redirect()->route('admin.home')
                : redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Procesa registro de nuevo usuario.
     */
    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'nombre'      => ['required', 'string', 'max:100'],
                'usuario' => [
                    'required',
                    'string',
                    'max:50',
                    'alpha_dash',
                    Rule::unique('usuarios', 'usuario')->where(function ($query) {
                        $query->whereNull('deleted_at');
                    }),
                ],
                'contrasena'  => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'contrasena.confirmed' => 'La confirmación de contraseña no coincide.',
                'contrasena.min'       => 'La contraseña debe tener al menos :min caracteres.',
            ]
        );

        $user = Usuario::create([
            'nombre'     => $data['nombre'],
            'usuario'    => $data['usuario'],
            'contrasena' => Hash::make($data['contrasena']),
            // 'rol' => 'jugador', // la DB ya define DEFAULT 'jugador'
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('ok', '¡Cuenta creada con éxito!');
    }
}
