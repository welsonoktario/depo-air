<?php

use App\Http\Controllers\API\DepoController;
use App\Http\Controllers\API\TransaksiController;
use Illuminate\Http\Request;
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

Route::group(['middleware' => [], 'as' => 'api.'], function () {
    Route::apiResource('depo', DepoController::class);
    Route::apiResource('transaksi', TransaksiController::class);
});
