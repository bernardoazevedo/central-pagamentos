<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
Route::get('/client/{id}', [ClientController::class, 'getClient'])->name('client.index');

Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
