<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/product', [ProductController::class, 'create'])->name('product.create');
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'get'])->name('product.get');
Route::patch('/product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/{id}', [ProductController::class, 'delete'])->name('product.delete');

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
Route::get('/client/{id}', [ClientController::class, 'get'])->name('client.get');

Route::post('/transaction', [TransactionController::class, 'create'])->name('transaction.create');
Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transaction/{id}', [TransactionController::class, 'get'])->name('transaction.get');
Route::post('/transaction/{id}/chargeback', [TransactionController::class, 'chargeback'])->name('transaction.chargeback');

Route::patch('/gateway/{id}', [GatewayController::class, 'update'])->name('gateway.update');

Route::post('/user', [UserController::class, 'create'])->name('user.create');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/{id}', [UserController::class, 'get'])->name('user.get');
Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user.delete');
