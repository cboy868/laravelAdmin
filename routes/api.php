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

require_once 'apiAdmin.php';
require_once 'apiMember.php';

//================================================
//全平台统一使用
// 取图片验证码
Route::get('captcha', 'ShowCaptcha')->middleware('api');
//取短信验证码
Route::post('smscode', 'ShowSmsCode')->middleware('api');
//=================================================


Route::get('home', 'HomeController@index')->middleware('api');
Route::get('discover', 'HomeController@discover')->middleware('api');
Route::get('profile', 'HomeController@profile')->middleware('api');

Route::get('wechat', 'Wechat\Controller@index');

Route::post('/login_by_code', 'User\AuthController@loginBySmsCode');

////会员
//Route::group([
//    'namespace' => 'member',
//    'prefix' => 'member'
//], function ($router) {
//    //确定版本
//    $version = \request('version');
//    $version = 'V' . intval($version);
//
//    //确定方法
//    $method = \request('method');
//    $method = str_replace('.', '_', $method);
//    $action = config('apimembermap.' . $version . '.' . $method);
//
//
//    if (!$action) {
//        return $router->post('/', 'V1\ErrorController@noApi');
//    }
//
//    //登录无需授权
//    if ($method == 'auth_member_login') {
//        return $router->post('/', $version .'\\'. $action);
//    }
//
//    //其它后台操作均需要授权
//    Route::middleware(['auth:member', 'auth.token'])->group(function ($router)use($version, $action) {
//        $router->post('/', $version .'\\'. $action);
//    });
//});


////mobile移动客户端
//Route::group([
//    'namespace' => 'mobile',
//    'prefix' => 'mobile'
//], function ($router) {
//    //确定版本
//    $version = \request('version');
//    $version = 'V' . intval($version);
//
//    //确定方法
//    $method = \request('method');
//    $method = str_replace('.', '_', $method);
//    $action = config('apimobilemap.' . $version . '.' . $method);
//
//
//    if (!$action) {
//        return $router->post('/', 'V1\ErrorController@noApi');
//    }
//
//    //登录无需授权
//    if ($method == 'auth_mobile_login') {
//        return $router->post('/', $version .'\\'. $action);
//    }
//
//    //其它后台操作均需要授权
////    Route::middleware(['auth:member', 'auth.token'])->group(function ($router)use($version, $action) {
//        $router->post('/', $version .'\\'. $action);
////    });
//});