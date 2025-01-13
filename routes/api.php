<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [CustomerController::class, 'login']);
Route::post('signup', [CustomerController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('search-products', [ProductController::class, 'search']);

Route::get('comments/{productId}', [CommentController::class, 'fetch']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('customer')->group(function () {
        Route::get('profile', function (Request $request) {
            return $request->user();
        });

        Route::get('orders', [CustomerController::class, 'getOrder']);
        Route::post('check-auth', [CustomerController::class, 'checkAuthenticated']);
        Route::get('logout', [CustomerController::class, 'logout']);
    });

    Route::prefix('comments')->group(function () {
        Route::post('/', [CommentController::class, 'create']);
        Route::put('/{commentId}', [CommentController::class, 'update']);
        Route::delete('/{commentId}', [CommentController::class, 'delete']);
    });

    Route::prefix('wishlist')->group(function () {
        Route::post('/', [WishListController::class, 'store']); // Add to wishlist
        Route::get('/', [WishListController::class, 'fetch']); // Get wishlist
        Route::delete('/{productId}', [WishListController::class, 'delete']); // Remove from wishlist
    });

    Route::prefix('carts')->group(function () {
        Route::post('/', [CartController::class, 'store']); // No {productId}, send data via the request body
        Route::get('/', [CartController::class, 'fetch']);
        Route::post('/delete', [CartController::class, 'delete']); // No {productId}, send data via the request body
    });
});
