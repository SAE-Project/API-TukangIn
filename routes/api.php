<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PenggunaController;
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
Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('getHargaPesanan',[PenggunaController::class,'getHargaLayanan']);
Route::post('showTukang',[PenggunaController::class,'showTukang']);
Route::post('pemesanan',[PenggunaController::class,'pemesanan']);
Route::post('changeTukangOrder',[PenggunaController::class,'changeTukangOrder']);
Route::post('showLayanan',[PenggunaController::class,'showLayanan']);
Route::post('menungguPembayaran',[PenggunaController::class,'menungguPembayaran']);
Route::post('menungguPembayaranCount',[PenggunaController::class,'menungguPembayaranCount']);
Route::post('pesananSaatIni',[PenggunaController::class,'pesananSaatIni']);
Route::post('pembayaran',[PenggunaController::class,'pembayaran']);
Route::post('batalkanPesanan',[PenggunaController::class,'batalkanPesanan']);
Route::post('invoice',[PenggunaController::class,'invoice']);
Route::post('isNotPaid',[PenggunaController::class,'isNotPaid']);
Route::get('getKategori',[PenggunaController::class,'getKategori']);
Route::post('getLayanan',[PenggunaController::class,'showLayananById']);
Route::post('menungguPembayaranJoin',[PenggunaController::class,'menungguPembayaranJoin']);
Route::post('menungguPembayaranJoinById',[PenggunaController::class,'menungguPembayaranJoinById']);
Route::post('showTukangJoinOrderId', [PenggunaController::class,'showTukangJoinOrderId']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
