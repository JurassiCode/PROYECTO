<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocumentacionController;
use App\Http\Middleware\EsAdmin;
use App\Http\Controllers\TrackeoPartidaController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\PartidasController;
use App\Http\Controllers\ColocacionesController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\PerfilController;

/*
|--------------------------------------------------------------------------
| Home público (siempre la landing)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Autenticación (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Logout (auth)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('admin.usuarios.index'))->name('home');
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
    });

/*
|--------------------------------------------------------------------------
| Jugador
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Jugador + Trackeo (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Pantalla principal de configuración de partida
    Route::get('/play', [PlayController::class, 'index'])->name('play');

    // Agregar jugador (POST)
    Route::post('/play/add', [PlayController::class, 'add'])->name('play.add');

    // Quitar jugador (POST con parámetro {id})
    Route::post('/play/remove/{id}', [PlayController::class, 'remove'])->name('play.remove');

    // Vaciar lista completa (POST)
    Route::post('/play/clear', [PlayController::class, 'clear'])->name('play.clear');

    // Crear partida definitiva (guarda en BD)
    Route::post('/partidas', [PartidasController::class, 'store'])->name('partidas.store');
});

// Ver trackeo de partida persistida
Route::get('/trackeo-partida/{partida}', [PartidasController::class, 'show'])
    ->whereNumber('partida')
    ->name('trackeo.partida.show');

// Registrar jugada (para cuando cableen la lógica)
Route::post('/partidas/{partida}/colocaciones', [ColocacionesController::class, 'store'])
    ->whereNumber('partida')
    ->name('colocaciones.store');

// Finalizar partida (medio implementado, corregir a futuro)
Route::post('/partidas/{partida}/finalizar', [PartidasController::class, 'finalizar'])
    ->whereNumber('partida')
    ->name('partidas.finalizar');

// Ver resultados de partida persistida
Route::get('/resultados-partida/{partida}', [ResultadosController::class, 'show'])
    ->whereNumber('partida')
    ->name('resultados.partida.show');


/*
|--------------------------------------------------------------------------
| Documentación
|--------------------------------------------------------------------------
*/
Route::get('/documentacion/{path?}', [DocumentacionController::class, 'index'])
    ->where('path', '.*')
    ->name('documentacion');


/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});


// 🎲 Tirar dado real (BD)
Route::post('/partidas/{partida}/tirar-dado', [PartidasController::class, 'tirarDado'])
    ->whereNumber('partida')
    ->name('partidas.tirarDado');

// 🦕 Agregar colocación (local)
// Registrar jugada (trackeo real)
Route::post('/partidas/{partida}/colocaciones', [PartidasController::class, 'agregarColocacion'])
    ->whereNumber('partida')
    ->name('partidas.agregarColocacion');
// ✅ Finalizar partida (local temporal)
Route::post('/trackeo/finalizar', function () {
    session()->forget(['partida.jugadores', 'colocaciones']);
    return back()->with('ok', '✅ Partida finalizada localmente.');
})->name('partidas.finalizar');
