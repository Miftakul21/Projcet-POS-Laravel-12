<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('test');
// });


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// pengguna
Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
Route::post('/pengguna-store', [PenggunaController::class, 'store']);
Route::post('/pengguna-update', [PenggunaController::class, 'update']);
Route::post('/pengguna-delete', [PenggunaController::class, 'delete']);
Route::get('/pengguna-get-data', [PenggunaController::class, 'getData']);
Route::get('/pengguna-get-detail/{id}', [PenggunaController::class, 'getDetail']);

// category
Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::post('/category-store', [CategoryController::class, 'store']);
Route::post('/category-update', [CategoryController::class, 'update']);
Route::post('/category-delete', [CategoryController::class, 'delete']);
Route::get('/category-get-data', [CategoryController::class, 'getData']);
Route::get('/category-get-detail/{id}', [CategoryController::class, 'getDetail']);
