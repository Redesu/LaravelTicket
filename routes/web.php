<?php

use App\Http\Controllers\Anexos\AnexoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Categorias\CategoriaController;
use App\Http\Controllers\Chamados\ChamadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Departamentos\DepartamentoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::prefix('/admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('admin.dashboard');
    Route::get('/chamados', [ChamadoController::class, 'showChamados'])->name('admin.chamados');
    Route::get('/chamados/{id}', [ChamadoController::class, 'showChamado'])->name('admin.chamados.show');
    Route::get('/categorias', [CategoriaController::class, 'showCategorias'])->name('admin.categorias');
    Route::get('/departamentos', [DepartamentoController::class, 'showDepartamentos'])->name('admin.departamentos');
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
    Route::get('/data-tables', [ChamadoController::class, 'getChamadosDataTablesData'])->name('api.chamados.data-tables');
    Route::post('/', [ChamadoController::class, 'insertChamado'])->name('api.chamados.post');
    Route::post('/{id}/comment', [ChamadoController::class, 'addComment'])->name('api.chamados.addComentario');
    Route::post('/{id}/solution', [ChamadoController::class, 'addSolution'])->name('api.chamados.addSolution');
    Route::put('/{id}', [ChamadoController::class, 'updateChamado'])->name('api.chamados.put');
    Route::delete('/', [ChamadoController::class, 'deleteChamado'])->name('api.chamados.delete');


    // Rotas adicionais
    Route::get('/stats/overview', [ChamadoController::class, 'getEstatisticas'])->name('api.chamados.stats');

});

Route::prefix('api/categorias')->middleware('auth')->group(function () {

    //CRUD
    Route::get('/data-tables', [CategoriaController::class, 'getCategoriasDataTablesData'])->name('api.categorias.data-tables');
    Route::post('/', [CategoriaController::class, 'insertCategoria'])->name('api.categorias.post');
    Route::put('/{id}', [CategoriaController::class, 'updateCategoria'])->name('api.categorias.put');
    Route::delete('/', [CategoriaController::class, 'deleteCategoria'])->name('api.categorias.delete');
});

Route::prefix('api/departamentos')->middleware('auth')->group(function () {

    //CRUD
    Route::get('/data-tables', [DepartamentoController::class, 'getDepartamentosDataTablesData'])->name('api.departamentos.data-tables');
    Route::post('/', [DepartamentoController::class, 'insertDepartamento'])->name('api.departamentos.post');
    Route::put('/{id}', [DepartamentoController::class, 'updateDepartamento'])->name('api.departamentos.put');
    Route::delete('/', [DepartamentoController::class, 'deleteDepartamento'])->name('api.departamentos.delete');
});

Route::prefix('anexos')->middleware('auth')->group(function () {
    Route::get('/{id}/download', [AnexoController::class, 'download'])->name('api.anexos.download');
});


