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

    $router->post('/smscode', 'ShowSmsCode')->middleware('api');
    $router->post('/login_by_code', 'User\AuthController@loginBySmsCode');


    $router->post('/wechat_order_create', 'Wechat\OrderController@create');


    $router->post('/login', 'User\AuthController@login');//没用的登录，暂时注掉
   // Route::middleware(['auth:member', 'auth.token'])->group(function ($router){

		Route::apiResource('pictures-user', 'Cms\PicturesUserController');
		Route::apiResource('pictures-category', 'Cms\PicturesCategoryController');
		Route::apiResource('pictures', 'Cms\PicturesController');
        Route::apiResource('cartoons', 'Cms\CartoonController');
        Route::get('chapter/{id}', 'Cms\CartoonController@chapter')->middleware('api');
        Route::get('pictures/favorite/{id}', 'Cms\PicturesController@favorite')->middleware('api');



        Route::apiResource('focus', 'Cms\FocusController');
        Route::apiResource('orders', 'Order\OrderController');

        //聚合页面
        Route::get('home', 'HomeController@index')->middleware('api');
        Route::get('discover', 'HomeController@discover')->middleware('api');
        Route::get('profile', 'HomeController@profile')->middleware('api');

        //套餐包
        Route::get('bag', 'HomeController@goods')->middleware('api');

        Route::get('me', 'User\AuthController@me');//个人信息

        Route::apiResource('goods', 'Goods\CategoryController');


        Route::apiResource('wechat', 'Wechat\IndexController');

        Route::get('wechat-auth', 'Wechat\Auth2Controller@auth');
        Route::get('wechat-auth-callback', 'Wechat\Auth2Controller@callBack');
        Route::get('wechat-order-create', 'Wechat\OrderController@create');
        Route::get('wechat-order-notify', 'Wechat\OrderController@notify');

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