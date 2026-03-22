<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('test');
// });


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
Route::post('/pengguna-store', [PenggunaController::class, 'store']);
Route::post('/pengguna-update', [PenggunaController::class, 'update']);
Route::post('/pengguna-delete', [PenggunaController::class, 'delete']);
Route::get('/pengguna-get-data', [PenggunaController::class, 'getData']);
Route::get('/pengguna-get-detail/{id}', [PenggunaController::class, 'getDetail']);
