<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocumentacionController;
use App\Http\Middleware\EsAdmin;
use App\Http\Controllers\LobbyController;
use App\Http\Controllers\PartidasController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RankingController;


/*
|--------------------------------------------------------------------------
| Home público (siempre la landing)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
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
    // Lobby de configuración de partida
    Route::get('/lobby', [LobbyController::class, 'index'])->name('lobby');
    Route::post('/lobby/add', [LobbyController::class, 'add'])->name('lobby.add');
    Route::post('/lobby/remove/{id}', [LobbyController::class, 'remove'])->name('lobby.remove');
    Route::post('/lobby/clear', [LobbyController::class, 'clear'])->name('lobby.clear');

    // Crear partida definitiva (guarda en BD)
    Route::post('/partidas', [PartidasController::class, 'store'])->name('partidas.store');
});


// Ver trackeo de partida persistida
Route::get('/trackeo-partida/{partida}', [PartidasController::class, 'show'])
    ->whereNumber('partida')
    ->name('trackeo.partida.show');

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
| Perfil de Usuario
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Perfil principal (dashboard del usuario)
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');

    // Editar perfil
    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');
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


//Ranking


Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
