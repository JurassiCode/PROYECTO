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
    // Módulo PLAY (gestiona jugadores de la partida en sesión)
    Route::get('/play', [PlayController::class, 'index'])->name('play');
    Route::post('/play/add', [PlayController::class, 'add'])->name('play.add');
    Route::post('/play/remove/{id}', [PlayController::class, 'remove'])->name('play.remove');
    Route::post('/play/clear', [PlayController::class, 'clear'])->name('play.clear');

    // Trackeo visual que usa los jugadores de la sesión
    Route::get('/trackeo-partida', [TrackeoPartidaController::class, 'index'])
        ->name('trackeo.partida.index');
});

// Crear partida desde /play (usa jugadores guardados en sesión)
Route::post('/partidas', [PartidasController::class, 'store'])->name('partidas.store');

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
