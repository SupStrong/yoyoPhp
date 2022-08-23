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
        // 模板
        Route::prefix('template')->group(function () {
            Route::post('/add', 'TemplateController@add');                                                                      // 添加
            Route::post('/update', 'TemplateController@update');                                                                // 修改
            Route::get('/find/{id}', 'TemplateController@find')->where('id', '[1-9]+');                                         // 单条记录                                                            // 修改
            Route::get('/delete/{id}/{userId}', 'TemplateController@delete')->where(['id' => '[1-9]+', 'userId' => '[1-9]+']);  // 删除
        });
        // 字体
        Route::prefix('font')->group(function () {
            Route::post('/add', 'FontController@add');                                                                      // 添加
            Route::post('/update', 'FontController@update');                                                                // 修改
            Route::get('/delete/{id}/{userId}', 'FontController@delete')->where(['id' => '[1-9]+', 'userId' => '[1-9]+']);  // 删除
            Route::get('/find/{id}', 'FontController@find')->where('id', '[1-9]+');                                         // 单条记录
        });
        // 查询
        Route::prefix('search')->group(function () {
            Route::post('/words', 'SearchController@words');  // 关键词
        });
    });
});

