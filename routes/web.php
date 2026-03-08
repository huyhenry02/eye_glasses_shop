<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.showIndex');
});
Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('showRegister');

    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
    Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/index', [ShopController::class, 'showIndex'])->name('showIndex');
        Route::get('/contact', [ShopController::class, 'showContact'])->name('showContact');
        Route::get('/products', [ShopController::class, 'showProducts'])->name('showProducts');
        Route::get('/product/{product}', [ShopController::class, 'showProductDetail'])->name('showProductDetail');

        Route::get('/cart', [CheckoutController::class, 'showCart'])->name('showCart');
        Route::post('/cart/add', [CheckoutController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update/{id}', [CheckoutController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/delete/{id}', [CheckoutController::class, 'deleteCart'])->name('cart.delete');

        Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('showCheckout');
        Route::post('/checkout', [CheckoutController::class, 'storeOrder'])->name('storeOrder');

        Route::get('/orders', [CheckoutController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show');
        Route::get('/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return')->middleware('auth');
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('employee')
            ->name('employee.')
            ->group(function () {
                Route::get('/', [EmployeeController::class, 'showIndex'])->name('showIndex');
                Route::get('/create', [EmployeeController::class, 'showCreate'])->name('showCreate');
                Route::get('/edit/{id}', [EmployeeController::class, 'showEdit'])->name('showEdit');

                Route::post('/create', [EmployeeController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [EmployeeController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [EmployeeController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('customer')
            ->name('customer.')
            ->group(function () {
                Route::get('/', [CustomerController::class, 'showIndex'])->name('showIndex');
                Route::get('/create', [CustomerController::class, 'showCreate'])->name('showCreate');
                Route::get('/edit/{id}', [CustomerController::class, 'showEdit'])->name('showEdit');

                Route::post('/create', [CustomerController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [CustomerController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [CustomerController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('category')
            ->name('category.')
            ->group(function () {
                Route::get('/', [CategoryController::class, 'showIndex'])->name('showIndex');
                Route::get('/create', [CategoryController::class, 'showCreate'])->name('showCreate');
                Route::get('/edit/{id}', [CategoryController::class, 'showEdit'])->name('showEdit');

                Route::post('/create', [CategoryController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [CategoryController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('product')
            ->name('product.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'showIndex'])->name('showIndex');
                Route::get('/create', [ProductController::class, 'showCreate'])->name('showCreate');
                Route::get('/edit/{id}', [ProductController::class, 'showEdit'])->name('showEdit');
                Route::get('/detail/{id}', [ProductController::class, 'showDetail'])->name('showDetail');

                Route::post('/create', [ProductController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [ProductController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('destroy');
            });
    });
