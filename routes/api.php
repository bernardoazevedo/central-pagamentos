<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
Route::get('/client/{id}', [ClientController::class, 'getClient'])->name('client.get');

Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transaction/{id}', [TransactionController::class, 'getTransaction'])->name('transaction.get');

Route::patch('/gateway/{id}', [GatewayController::class, 'edit'])->name('gateway.edit');

Route::post('/user', [UserController::class, 'create'])->name('user.create');
