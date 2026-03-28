<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController;
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
        Route::get('/blogs', [ShopController::class, 'showBlog'])->name('showBlog');
        Route::get('/blog/{slug}', [ShopController::class, 'showBlogDetail'])->name('showBlogDetail');
        Route::get('/products', [ShopController::class, 'showProducts'])->name('showProducts');
        Route::get('/product/{product}', [ShopController::class, 'showProductDetail'])->name('showProductDetail');

        Route::get('/cart', [CheckoutController::class, 'showCart'])->name('showCart')->middleware('auth');
        Route::post('/cart/add', [CheckoutController::class, 'addToCart'])->name('cart.add')->middleware('auth');
        Route::post('/cart/update/{id}', [CheckoutController::class, 'updateCart'])->name('cart.update')->middleware('auth');
        Route::delete('/cart/delete/{id}', [CheckoutController::class, 'deleteCart'])->name('cart.delete')->middleware('auth');

        Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('showCheckout')->middleware('auth');
        Route::post('/checkout', [CheckoutController::class, 'storeOrder'])->name('storeOrder')->middleware('auth');

        Route::get('/orders', [CheckoutController::class, 'index'])->name('orders.index')->middleware('auth');
        Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show')->middleware('auth');
        Route::get('/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('employee')
            ->name('employee.')
            ->group(function () {
                Route::get('/', [EmployeeController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách nhân viên')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Nhân viên');

                Route::get('/create', [EmployeeController::class, 'showCreate'])
                    ->name('showCreate')
                    ->defaults('page_title', 'Thêm mới nhân viên')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Nhân viên')
                    ->defaults('page_action', 'Thêm mới');

                Route::get('/edit/{id}', [EmployeeController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật nhân viên')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Nhân viên')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::post('/create', [EmployeeController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [EmployeeController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [EmployeeController::class, 'destroy'])->name('destroy');
            });
        Route::prefix('dashboard')
            ->name('dashboard.')
            ->group(function () {
                Route::get('/', [DashboardController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Tổng quan')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Tổng quan');
            });

        Route::prefix('customer')
            ->name('customer.')
            ->group(function () {
                Route::get('/', [CustomerController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách khách hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Khách hàng');

                Route::get('/create', [CustomerController::class, 'showCreate'])
                    ->name('showCreate')
                    ->defaults('page_title', 'Thêm mới khách hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Khách hàng')
                    ->defaults('page_action', 'Thêm mới');

                Route::get('/edit/{id}', [CustomerController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật khách hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Khách hàng')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::post('/create', [CustomerController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [CustomerController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [CustomerController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('category')
            ->name('category.')
            ->group(function () {
                Route::get('/', [CategoryController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách danh mục')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Danh mục');

                Route::get('/create', [CategoryController::class, 'showCreate'])
                    ->name('showCreate')
                    ->defaults('page_title', 'Thêm mới danh mục')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Danh mục')
                    ->defaults('page_action', 'Thêm mới');

                Route::get('/edit/{id}', [CategoryController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật danh mục')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Danh mục')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::post('/create', [CategoryController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [CategoryController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('product')
            ->name('product.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách sản phẩm')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Sản phẩm');

                Route::get('/create', [ProductController::class, 'showCreate'])
                    ->name('showCreate')
                    ->defaults('page_title', 'Thêm mới sản phẩm')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Sản phẩm')
                    ->defaults('page_action', 'Thêm mới');

                Route::get('/edit/{id}', [ProductController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật sản phẩm')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Sản phẩm')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::get('/detail/{id}', [ProductController::class, 'showDetail'])
                    ->name('showDetail')
                    ->defaults('page_title', 'Chi tiết sản phẩm')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Sản phẩm')
                    ->defaults('page_action', 'Chi tiết');

                Route::post('/create', [ProductController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [ProductController::class, 'update'])->name('update');
                Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('order')
            ->name('order.')
            ->group(function () {
                Route::get('/', [OrderController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách đơn hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Đơn hàng');

                Route::get('/detail/{id}', [OrderController::class, 'showDetail'])
                    ->name('showDetail')
                    ->defaults('page_title', 'Chi tiết đơn hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Đơn hàng')
                    ->defaults('page_action', 'Chi tiết');

                Route::get('/edit/{id}', [OrderController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật đơn hàng')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Đơn hàng')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::post('/edit/{id}', [OrderController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('invoice')
            ->name('invoice.')
            ->group(function () {
                Route::get('/', [InvoiceController::class, 'showIndex'])
                    ->name('showIndex')
                    ->defaults('page_title', 'Danh sách hóa đơn')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Hóa đơn');

                Route::get('/create', [InvoiceController::class, 'showCreate'])
                    ->name('showCreate')
                    ->defaults('page_title', 'Thêm mới hóa đơn')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Hóa đơn')
                    ->defaults('page_action', 'Thêm mới');

                Route::get('/edit/{id}', [InvoiceController::class, 'showEdit'])
                    ->name('showEdit')
                    ->defaults('page_title', 'Cập nhật hóa đơn')
                    ->defaults('page_parent', 'Quản trị')
                    ->defaults('page_current', 'Hóa đơn')
                    ->defaults('page_action', 'Chỉnh sửa');

                Route::post('/create', [InvoiceController::class, 'store'])->name('store');
                Route::post('/edit/{id}', [InvoiceController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
                Route::get('/vnpay-return', [InvoiceController::class, 'vnpayReturn'])->name('vnpay.return');
            });
    });
