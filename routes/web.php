<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
// Import Controller yang digunakan
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController; // Controller yang menangani Level
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TableController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua rute ini dimuat oleh RouteServiceProvider dan diberikan ke "web" middleware group.
|
*/

Route::get('/', [WelcomeController::class, 'index']);

// =========================================================================
// RUTE MANAJEMEN ADMIN & DATA DASAR
// =========================================================================

// 1. Route untuk Manajemen Level Pedas
Route::group(['prefix' => 'admin/levels'], function () {
    Route::get('/', [LevelController::class, 'index']); // GET /admin/levels
    Route::get('/list', [LevelController::class, 'list']); // GET /admin/levels/list
    Route::get('/create', [LevelController::class, 'create']);
    Route::post('/', [LevelController::class, 'store']);
    Route::get('/{id}', [LevelController::class, 'show']);
    Route::get('/{id}/edit', [LevelController::class, 'edit']);
    Route::put('/{id}', [LevelController::class, 'update']);
    Route::delete('/{id}', [LevelController::class, 'destroy']);
});

// 3. Route untuk Manajemen Kategori (Menggunakan Resource Route)
// URL: /admin/categories
Route::prefix('admin/categories')->group(function () {
    Route::get('/list', [CategoryController::class, 'list']);
});
Route::resource('admin/categories', CategoryController::class);


// 4. Route untuk Manajemen Menu
// URL: /admin/menus
Route::group(['prefix' => 'admin/menus'], function () {
    Route::get('/', [MenuController::class, 'index']);
    Route::get('/list', [MenuController::class, 'list']);
    Route::get('/create', [MenuController::class, 'create']);
    Route::post('/', [MenuController::class, 'store']);
    Route::get('/{id}', [MenuController::class, 'show']);
    Route::get('/{id}/edit', [MenuController::class, 'edit']);
    Route::put('/{id}', [MenuController::class, 'update']);
    Route::delete('/{id}', [MenuController::class, 'destroy']);
});

// 5. Route untuk Manajemen Pesanan
// URL: /admin/orders
Route::group(['prefix' => 'admin/orders'], function () {
    Route::get('/', [OrderController::class, 'index']); 
    Route::get('/list', [OrderController::class, 'list']);
    Route::get('/{id}', [OrderController::class, 'show']);
    // Rute untuk update status pesanan
    Route::put('/{id}/status', [OrderController::class, 'updateStatus']); 
});

// Route untuk Manajemen Meja
Route::group(['prefix' => 'admin/tables'], function () {
    Route::get('/', [TableController::class, 'index']);
    Route::get('/list', [TableController::class, 'list']);
    Route::get('/create', [TableController::class, 'create']);
    Route::post('/', [TableController::class, 'store']);
    Route::get('/{id}', [TableController::class, 'show']);
    Route::get('/{id}/edit', [TableController::class, 'edit']);
    Route::put('/{id}', [TableController::class, 'update']);
    Route::delete('/{id}', [TableController::class, 'destroy']);
});
// =========================================================================
// RUTE API (CHECKOUT DARI ANDROID)
// =========================================================================

// URL: /api/order/checkout
Route::post('/api/order/checkout', [OrderController::class, 'store']);