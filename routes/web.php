<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\cashierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [adminController::class, 'index']);
    Route::get('/products', [adminController::class, 'products']);
    Route::get('/manage-stocks', [adminController::class, 'stocks']);
    Route::post('/products/categories', [adminController::class, 'store']);
    Route::get('/manage-categories', [adminController::class, 'categories']);
    Route::delete('/products/categories/{categories}', [adminController::class, 'destroy']);
    Route::put('/products/categories/{categories}', [adminController::class, 'update']);

});

Route::middleware(['auth', 'verified', 'cashier'])->group(function () {
    // Route::get('/cashier/dashboard', [cashierController::class, 'index']);

});

require __DIR__.'/settings.php';
