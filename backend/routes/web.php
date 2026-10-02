<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get(
    '/dashboard',
    [CustomerDashboardController::class, 'index']
)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get(
        '/checkout/success/{order}',
        [CheckoutController::class, 'success']
    )->name('checkout.success');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::patch(
        '/orders/{order}/cancel',
        [OrderController::class, 'cancel']
    )->name('orders.cancel');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');

        Route::resource(
            'categories',
            AdminCategoryController::class
        );

        Route::resource(
            'products',
            AdminProductController::class
        );

        Route::resource(
            'orders',
            AdminOrderController::class
        )->only([
            'index',
            'show',
            'update',
        ]);

        Route::get(
            '/customers',
            [AdminUserController::class, 'index']
        )->name('customers.index');

        Route::get(
            '/customers/{user}',
            [AdminUserController::class, 'show']
        )->name('customers.show');
    });

require __DIR__.'/auth.php';