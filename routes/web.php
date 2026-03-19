<?php

use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetWebController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('banner.index');
    Route::get('create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('store', [BannerController::class, 'store'])->name('banner.store');
    Route::get('edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('update/{id}', [BannerController::class, 'update'])->name('banner.update');
    Route::get('show/{id}', [BannerController::class, 'show'])->name('banner.show');
    Route::delete('destroy/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');
});
Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/show/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
});
Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('order.index');
    Route::get('/create', [OrderController::class, 'create'])->name('order.create');
    Route::post('/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/detail/{id}', [OrderController::class, 'detail'])->name('order.detail');
    Route::post('/update/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/contact', [OrderController::class, 'contact'])->name('order.contact');
    Route::get('/showuser/{id}', [OrderController::class, 'showuser'])->name('order.showuser');
    Route::delete('/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::put('/order/{id}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
});
Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/show/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::delete('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/show/{id}', [UserController::class, 'show'])->name('user.show');
    Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});
// THÊM DÒNG NÀY → CÓ name('emails.orders.password-reset')
Route::get('/reset-password/{token}', [PasswordResetWebController::class, 'show'])
    ->name('emails.orders.password-reset')
    ->middleware('guest');

Route::post('/reset-password', [PasswordResetWebController::class, 'reset'])
    ->name('password.update');
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');
