<?php

use Illuminate\Http\Request;

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

Route::group([
   'prefix' => 'client'
], function ($router){

   $router->post('/login_by_code', 'User\AuthController@loginBySmsCode');

    $router->post('/login', 'User\AuthController@login');//没用的登录，暂时注掉
   // Route::middleware(['auth:member', 'auth.token'])->group(function ($router){

		Route::apiResource('pictures-user', 'Cms\PicturesUserController');
		Route::apiResource('pictures-category', 'Cms\PicturesCategoryController');
		Route::apiResource('pictures', 'Cms\PicturesController');

        Route::apiResource('focus', 'Cms\FocusController');

   // });
});


//
//

















////方法一，通过读取配置获取路由
//Route::group([
//    'namespace' => 'admin',
//    'prefix' => 'admin'
//], function ($router) {
//    //确定版本
//    $version = \request('version');
//    $version = 'V' . intval($version);
//
//    //确定方法
//    $method = \request('method');
//    $method = str_replace('.', '_', $method);
//    $action = config('apiadminmap.' . $version . '.' . $method);
//
//    if (!$action) {
//        return $router->post('/', 'V1\ErrorController@noApi');
//    }
//
//    //登录无需授权
//    if ($method == 'auth_admin_login') {
//        return $router->post('/', $version .'\\'. $action);
//    }
//
//    //其它后台操作均需要授权
////    Route::middleware(['auth:admin', 'auth.token'])->group(function ($router)use($version, $action) {
//    $router->post('/', $version .'\\'. $action);
////    });
//});