<?php

use App\Http\Controllers\API\TransaksiController as APITransaksiController;
use App\Http\Controllers\Web\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DepoController;
use App\Http\Controllers\Web\InventoriController;
use App\Http\Controllers\Web\KategoriController;
use App\Http\Controllers\Web\TransaksiController;

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

Route::get('/test', [APITransaksiController::class, 'store']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('inventori', InventoriController::class)->parameter('inventori', 'barang');

    Route::resources([
        'depo' => DepoController::class,
        'barang' => BarangController::class,
        'kategori' => KategoriController::class,
        'transaksi' => TransaksiController::class,
    ]);
});

require __DIR__ . '/auth.php';
