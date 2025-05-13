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
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/produto', [ProductController::class, 'index'])->name('produto');
    Route::get('/relatorio', [ReportController::class, 'index'])->name('relatorio');
    Route::get('/venda', [VendaController::class, 'index'])->name('venda');
    Route::get('/caixa', [CaixaController::class, 'index'])->name('caixa');
});
