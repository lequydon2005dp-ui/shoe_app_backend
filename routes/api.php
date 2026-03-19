<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PaymentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('banner-list', [BannerController::class, 'list']);
Route::get('category-list', [CategoryController::class, 'list']);
Route::get('product-list', [ProductController::class, 'list']);
Route::get('/products/{id}', [ProductController::class, 'details']);
Route::get('order-list/{userid}', [OrderController::class, 'list']);
Route::get('user-row', [UserController::class, 'row']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Quản lý giỏ hàng (ĐÃ SỬA LỖI)
    // GET /api/cart (Lấy giỏ hàng)
    // Cart (Các route này khớp với CartController mới)
    Route::get('/cart', [CartController::class, 'index']);       // Lấy giỏ hàng
    Route::post('/cart/add', [CartController::class, 'store']);      // Thêm sản phẩm
    Route::post('/cart/decrease', [CartController::class, 'decrease']);  // Giảm số lượng
    Route::post('/cart/remove', [CartController::class, 'remove']);      // Xóa sản phẩm

    //User
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/update-avatar', [UserController::class, 'updateAvatar']);
    Route::post('/change-password', [UserController::class, 'changePassword']);

    // Quản lý đơn hàng
    Route::get('/orders', [OrderController::class, 'list']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/geocode', [MapController::class, 'geocode']);
    Route::post('/places-autocomplete', [MapController::class, 'placesAutocomplete']);
    Route::post('/place-details', [MapController::class, 'placeDetails']);

    // HỦY ĐƠN HÀNG – ĐÚNG ĐỊNH DẠNG
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->where('order', '[0-9]+')
        ->name('cancel'); // → route('api.orders.cancel', 24)

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{product_id}', [FavoriteController::class, 'destroy']);

    // Thêm route toggle (tối ưu cho frontend)
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
    Route::post('/favorites/sync', [FavoriteController::class, 'sync']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
