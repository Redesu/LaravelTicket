<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Chamados\ChamadoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::prefix('/admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('admin.dashboard');
    Route::get('/chamados', [ChamadoController::class, 'index'])->name('admin.chamados');
    Route::get('/chamados/{id}', [ChamadoController::class, 'showChamado'])->name('admin.chamados.show');
});


Route::prefix('/auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::prefix('api/chamados')->middleware('auth')->group(function () {

    // CRUD
    Route::get('/data-tables', [ChamadoController::class, 'getDataTablesData'])->name('api.chamados.data-tables');
    Route::post('/', [ChamadoController::class, 'insertChamado'])->name('api.chamados.post');
    Route::post('/{id}/comment', [ChamadoController::class, 'addComment'])->name('api.chamados.addComentario');
    Route::post('/{id}/solution', [ChamadoController::class, 'addSolution'])->name('api.chamados.addSolution');
    Route::put('/{id}', [ChamadoController::class, 'updateChamado'])->name('api.chamados.put');
    Route::delete('/', [ChamadoController::class, 'deleteChamado'])->name('api.chamados.delete');


    // Rotas adicionais
    Route::get('/stats/overview', [ChamadoController::class, 'getEstatisticas'])->name('api.chamados.stats');

});