<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhooksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::view('/', 'welcome');

Route::get('/tienda', [ProductController::class, 'index'])->name('products.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/carritos', [CartController::class, 'index'])->name('carts.index');

    Route::get('/carritos/{cart}', [CartController::class, 'show'])->name('carts.show');

    Route::get('/carritos/{id}/ordenar', [OrderController::class, 'create'])->name('orders.create');

    Route::get('/carritos/{id}/ordenar/{order}', [OrderController::class, 'payOrder'])->name('orders.pay');

    Route::get('/pedido/{order}/estado', [OrderController::class, 'orderComplete'])->name('orders.complete');

    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/facturas/{name}', [OrderController::class, 'showInvoice'])->name('orders.invoice');
});

Route::post('/webhooks', WebhooksController::class)->name('webhooks');

require __DIR__.'/auth.php';
