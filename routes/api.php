<?php

use App\Http\Controllers\API\AlamatController;
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
    Route::patch('{user}', [AuthController::class, 'update'])->name('auth.update');
    Route::patch('{user}/password', [AuthController::class, 'password'])->name('auth.password');
});

Route::group(['middleware' => ['auth:sanctum'], 'as' => 'api.'], function () {
    Route::patch('alamat/{alamat}/utama', [AlamatController::class, 'updateUtama']);
    Route::post('transaksi/{transaksi}/upload', [TransaksiController::class, 'uploadBukti'])->name('transaksi.upload');
    Route::post('transaksi/keranjang', [TransaksiController::class, 'addToKeranjang']);

    Route::apiResource('alamat', AlamatController::class);
    Route::apiResource('barang', BarangController::class);
    Route::apiResource('depo', DepoController::class);
    Route::apiResource('transaksi', TransaksiController::class);
    Route::apiResource('keranjang', KeranjangController::class)->parameter('keranjang', 'barang');
});
