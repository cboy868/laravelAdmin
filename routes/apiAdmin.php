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
//Route::apiResource('novels', 'NovelController');


Route::group([
    'prefix' => 'admin'
], function ($router){


    $router->post('/login', 'Admin\AuthController@login');



//    Route::middleware(['auth:admin', 'auth.token'])->group(function ($router){
        $router->post('/me', 'Admin\AuthController@me');//个人信息
        $router->post('/logout', 'Admin\AuthController@logout');//登出

        Route::apiResource('posts', 'Cms\PostController');
        Route::apiResource('folders', 'Admin\V1\FolderController');
        Route::apiResource('files', 'Admin\V1\PictureController');

        Route::apiResource('pictures-category', 'Cms\PicturesCategoryController');
        Route::apiResource('pictures-user', 'Cms\PicturesUserController');
        Route::apiResource('pictures', 'Cms\PicturesController');
//    });
});
//
////方法一，通过读取配置获取路由
//Route::group([
//    'namespace' => 'admin',
//    'prefix' => 'admin'
//], function ($router) {
//    //确定版本
//    $version = \request('version');
//    $version = 'V' . intval($version);
//    $med = \request()->method();
//
//    //确定方法
//    $method = \request('method');
//    $method = \request()->header('method');
//
////    dd($method);
//    $version = 'V1';
//
//
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