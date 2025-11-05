<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
