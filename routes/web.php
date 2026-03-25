<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

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

// supplier
Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
Route::post('/supplier-store', [SupplierController::class, 'store']);
Route::post('/supplier-update', [SupplierController::class, 'update']);
Route::post('/supplier-delete', [SupplierController::class, 'delete']);
Route::get('/supplier-get-data', [SupplierController::class, 'getData']);
Route::get('/supplier-get-detail/{id}', [SupplierController::class, 'getDetail']);

// barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang');
Route::post('/barang-store', [BarangController::class, 'store']);
Route::post('/barang-update', [BarangController::class, 'update']);
Route::post('/barang-delete', [BarangController::class, 'delete']);
Route::get('/barang-get-data', [BarangController::class, 'getData']);
Route::get('/barang-get-detail/{id}', [BarangController::class, 'getDetail']);