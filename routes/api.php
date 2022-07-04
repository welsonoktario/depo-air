<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BarangController;
use App\Http\Controllers\API\DepoController;
use App\Http\Controllers\API\KeranjangController;
use App\Http\Controllers\API\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'api.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
});

Route::group(['middleware' => ['auth:sanctum'], 'as' => 'api.'], function () {
    Route::apiResource('barang', BarangController::class);
    Route::apiResource('depo', DepoController::class);
    Route::apiResource('transaksi', TransaksiController::class);
    Route::apiResource('keranjang', KeranjangController::class);
});
