<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\cashierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::get('/redirect', function () {
    if (auth()->user()->role == 'admin') {
        return redirect('/dashboard');
    } elseif (auth()->user()->role == 'cashier') {
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
    Route::put('/product/edit/{product}', [adminController::class, 'editProduct']);

});

Route::middleware(['auth', 'verified', 'cashier'])->group(function () {
    Route::get('/cashier/dashboard', [cashierController::class, 'index']);
    Route::post('/cart/add', [cashierController::class, 'addToCart']);
    Route::get('/cart/show', [cashierController::class, 'showCart']);
    Route::delete('/cart/delete/{cartItem}', [cashierController::class, 'deleteCartItems']);
    Route::post('/cashier/pay', [CashierController::class, 'payOrder'])->name('cashier.pay');
    Route::get('/cashier/receipt', [CashierController::class, 'receipt'])->name('cashier.receipt');
    Route::delete('/cart/clear', [cashierController::class, 'clearCart']);
    Route::get('/cashier/purchase-history', [cashierController::class, 'orderHistory']);


});

require __DIR__.'/settings.php';
