<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;

Route::get('/menu', [MenuController::class, 'index']);

Route::post('/order', [OrderController::class, 'store']);

// --- TAMBAHKAN INI (Biar Android gak kena 404 pas mau bayar) ---
Route::post('/order/{id}/pay', [OrderController::class, 'markAsPaid']);