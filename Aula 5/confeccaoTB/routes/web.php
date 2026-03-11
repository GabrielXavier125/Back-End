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
Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
// Rota para receber os dados atualizados e salvar no banco
Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
// Rota para excluir um cliente
Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

// additional resource listings
Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
Route::get('/fornecedores/create', [FornecedorController::class, 'create'])->name('fornecedores.create');
Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('fornecedores.store');
Route::get('/fornecedores/{fornecedor}/edit', [FornecedorController::class, 'edit'])->name('fornecedores.edit');
Route::put('/fornecedores/{fornecedor}', [FornecedorController::class, 'update'])->name('fornecedores.update');
Route::delete('/fornecedores/{fornecedor}', [FornecedorController::class, 'destroy'])->name('fornecedores.destroy');
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{produto}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');
Route::get('/estoques', [EstoqueController::class, 'index'])->name('estoques.index');
Route::get('/estoques/create', [EstoqueController::class, 'create'])->name('estoques.create');
Route::post('/estoques', [EstoqueController::class, 'store'])->name('estoques.store');
Route::get('/estoques/{estoque}/edit', [EstoqueController::class, 'edit'])->name('estoques.edit');
Route::put('/estoques/{estoque}', [EstoqueController::class, 'update'])->name('estoques.update');
Route::delete('/estoques/{estoque}', [EstoqueController::class, 'destroy'])->name('estoques.destroy');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
Route::get('/pedidos/{pedido}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
Route::put('/pedidos/{pedido}', [PedidoController::class, 'update'])->name('pedidos.update');
Route::delete('/pedidos/{pedido}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
