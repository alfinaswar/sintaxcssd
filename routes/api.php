<?php

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
