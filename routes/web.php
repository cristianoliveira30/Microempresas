<?php

use App\Http\Controllers\CaixaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'web',
    'verified', // tira permissões de usuários sem email verificado
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    // pages
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard'); // página inicial do sistema
    Route::get('/produto', [ProductController::class, 'index'])->name('produto'); // página de produtos
    Route::get('/relatorio', [ReportController::class, 'index'])->name('relatorio'); // página de relatórios
    Route::get('/venda', [VendaController::class, 'index'])->name('venda'); // página de vendas
    Route::get('/caixa', [CaixaController::class, 'index'])->name('caixa'); // página do caixa

    // data
    Route::get('/pedidos/data', [CaixaController::class, 'getPedidoData'])->name('pedidos.data'); // envia pedidos pendentes
    Route::post('/pedido/pagar/{id}', [CaixaController::class, 'pagarPedido'])->name('pedido.pagar'); // paga pedido

    // methods
    Route::post('/venda', [VendaController::class, 'store'])->name('pedido.store'); // insere pedidos
    Route::post('/venda/{pedido}', [VendaController::class, 'update'])->name('pedido.update'); // atualiza pedidos
    Route::delete('/caixa/pedido/{pedido}/produto/{produto}', [CaixaController::class, 'destroyProdutoPedido'])->name('pedido.produto.destroy'); // deleta pedidos

    //tests
    Route::post('/teste', function () {
        return response()->json(['ok' => true]);
    })->name('teste');
});
