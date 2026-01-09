<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\cashierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::get('/redirect', function () {
    if(auth()->user()->role == 'admin'){
        return redirect('/dashboard');
    } elseif(auth()->user()->role == 'cashier'){
        return redirect('/cashier/dashboard');
    } else {
        abort(403);
    }
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [adminController::class, 'index']);
    Route::get('/products', [adminController::class, 'products']);
    Route::get('/manage-stocks', [adminController::class, 'showProducts']);
    Route::post('/products/categories', [adminController::class, 'store']);
    Route::get('/manage-categories', [adminController::class, 'categories']);
    Route::delete('/products/categories/{categories}', [adminController::class, 'destroy']);
    Route::put('/products/categories/{categories}', [adminController::class, 'update']);
    Route::post('/add-menu', [adminController::class, 'storeProducts']);
    Route::delete('/product/{product}', [adminController::class, 'destroyProduct']);
    Route::post('/product/add-stock/{product}', [adminController::class, 'addStocks']);
    Route::get('/manage-stocks', [adminController::class, 'manageStocks']);

});

Route::middleware(['auth', 'verified', 'cashier'])->group(function () {
    Route::get('/cashier/dashboard', [cashierController::class, 'index']);

});

require __DIR__.'/settings.php';
