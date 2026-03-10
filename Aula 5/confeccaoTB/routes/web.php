<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

// Rotas para estrutura de clientes para cadastro, edição e exclusão
// Rota para mostrar o formulário
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
// Rota para receber os dados e salvar no banco
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
// Rota para mostrar o formulário de edição
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
// Rota para receber os dados atualizados e salvar no banco
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
// Rota para excluir um cliente
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

// additional resource listings
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::get('/estoques', [EstoqueController::class, 'index'])->name('estoques.index');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
