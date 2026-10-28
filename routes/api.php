<?php

use App\Http\Controllers\Api\InventarisController;
use App\Http\Controllers\ApiSinta;
use App\Http\Controllers\DataInventarisController;
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
Route::middleware(['api.rs', 'throttle:30,1'])
    ->get('inventaris', [ApiSinta::class, 'DataInventaris']);

/*
|--------------------------------------------------------------------------
| API Inventaris (read-only, ter-scope per rumah sakit)
|--------------------------------------------------------------------------
| Autentikasi: header X-API-Username dan X-API-Password (akun di master_rs).
| Setiap akun hanya dapat membaca data milik kodeRS-nya sendiri.
*/
Route::middleware(['api.rs', 'throttle:api.inventaris'])
    ->prefix('v1/inventaris')
    ->group(function () {
        Route::get('/', [InventarisController::class, 'index']);
        Route::get('/{kodeItem}', [InventarisController::class, 'show']);
    });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
