<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/test', function (Request $request) {
//     echo 'test',PHP_EOL;
// });

Route::namespace('App\Http\Controllers\Api')->group(function(){
    /**
     * 无任何认证
     */
    Route::prefix('oauth')->group(function () {
        Route::get('/apiToken', 'OauthController@apiToken');
    });

    /**
     * apiToken 认证路由
     */
    Route::middleware(['api.token'])->group(function () {
        Route::get('/', function () {
        });
    });
});

