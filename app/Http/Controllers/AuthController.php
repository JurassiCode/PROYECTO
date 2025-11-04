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
    /** --- LOGIN --- */

    /** Muestra el login si no está autenticado; si lo está, redirige según rol. */
    public function show(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->rol === 'admin'
                ? redirect()->route('admin.home')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    /** Procesa el login con nickname + contraseña. */
    public function login(Request $request)
    {
        $cred = $request->validate(
            [
                'nickname'   => ['required', 'string'],
                'contrasena' => ['required', 'string'],
            ],
            [
                'nickname.required'   => __('The username field is required.'),
                'nickname.string'     => __('The username must be a valid text.'),
                'contrasena.required' => __('The password is required.'),
                'contrasena.string'   => __('The password must be a valid text.'),
            ]
        );

        // 1️⃣ Buscar usuario por nickname
        $usuario = Usuario::where('nickname', $cred['nickname'])->first();

        if (!$usuario) {
            return back()
                ->withErrors(['nickname' => __('Incorrect username or password.')])
                ->onlyInput('nickname');
        }

        // 2️⃣ Verificar si está desactivado
        if (!is_null($usuario->deleted_at)) {
            return back()
                ->withErrors(['nickname' => __('This user has been deactivated.')])
                ->onlyInput('nickname');
        }

        // 3️⃣ Verificar contraseña
        if (!Hash::check($cred['contrasena'], $usuario->contrasena)) {
            return back()
                ->withErrors(['contrasena' => __('Incorrect username or password.')])
                ->onlyInput('nickname');
        }

        // 4️⃣ Autenticar
        Auth::login($usuario);
        $request->session()->regenerate();

        // 5️⃣ Redirecciones según contexto
        if ($request->filled('next') && Str::startsWith($request->next, '/')) {
            return redirect($request->next);
        }

        if (str_contains(url()->previous(), '/admin') && Auth::user()->rol === 'admin') {
            return redirect()->route('admin.home');
        }

        return redirect()->route('home');
    }

    /** Cierra sesión. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /** --- REGISTER --- */

    /** Muestra el formulario de registro si no está autenticado. */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->rol === 'admin'
                ? redirect()->route('admin.home')
                : redirect()->route('home');
        }

        return view('auth.register');
    }

    /** Procesa registro de nuevo usuario. */
    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:100'],
                'nickname' => [
                    'required',
                    'string',
                    'max:50',
                    'alpha_dash',
                    Rule::unique('usuarios', 'nickname')->where(fn($q) => $q->whereNull('deleted_at')),
                ],
                'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'contrasena.confirmed' => __('Password confirmation does not match.'),
                'contrasena.min'       => __('The password must be at least :min characters.'),
            ]
        );

        $user = Usuario::create([
            'nombre'     => $data['nombre'],
            'nickname'   => $data['nickname'],
            'contrasena' => Hash::make($data['contrasena']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('ok', __('Account created successfully!'));
    }
}
