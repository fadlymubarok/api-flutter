<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/api')->group(function () {
    // barang
    Route::prefix('/barang')->group(function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::get('/pasar_rebo', [BarangController::class, 'getPasarRebo']);
        Route::get('/pakansari', [BarangController::class, 'getPakansari']);
        Route::post('/', [BarangController::class, 'store']);
        Route::delete('/{id}', [BarangController::class, 'delete']);
    });

    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'storeOrUpdate']);
        Route::delete('/', [CartController::class, 'destroy']);
    });

    // cart
    // Route::resource('/cart', CartController::clas    s)->except(['create', 'show', 'edit']);
});
