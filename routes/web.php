<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\cashierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [adminController::class, 'index']);
    Route::get('/products', [adminController::class, 'products']);
    Route::get('/stocks', [adminController::class, 'stocks']);
    Route::post('/category', [adminController::class, 'store']);
    Route::get('/category', [adminController::class, 'show']);
});

Route::middleware(['auth', 'verified', 'cashier'])->group(function () {
    // Route::get('/cashier/dashboard', [cashierController::class, 'index']);

});

require __DIR__.'/settings.php';
