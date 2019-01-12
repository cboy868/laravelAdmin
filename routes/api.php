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
//方法一，通过读取配置获取路由
Route::group([
    'namespace' => 'api\admin',
    'middleware' => 'api',
    'prefix' => 'admin'
], function ($router) {
    //确定版本
    $version = \request('version');
    $version = 'V' . intval($version);

    //确定方法
    $method = \request('method');
    $action = config('apiadminmap.' . $version . '.' . str_replace('.', '_', $method));

    if (!$action) {
        return $router->post('/', 'V1\ErrorController@noApi');
    }
    $router->post('/', $version .'\\'. $action);
});


//方法二，通过计算获取路由
//Route::group([
//    'namespace' => 'api\admin',
//    'prefix' => 'admin'
//], function ($router) {
//    $adminMap = config('apiadminmap');
//
//    //确定方法
//    $method = \request('method');
//    $methodStr = "V1\ErrorController@noApi";
//
//    //确定版本
//    $version = \request('version');
//    $version = 'V' . intval($version);
//
//    $method = str_replace_last('.', 'Controller@', $method);
//    $methodArr = explode('.',$method);
//
//    $methodStr = '';
//    array_walk($methodArr, function ($item, $key) use(&$methodStr) {
//        $methodStr .= '\\' . ucfirst($item);
//    });
//
//    //如果计算出的路由不存在  怎么办呢
//
//    $router->post('/', $version  .$methodStr);
//});

//Route::group([
//	'namespace' => 'api\admin\V1',
//    'prefix' => 'admin'
//], function ($router) {
//    Route::post('login', 'AuthController@login');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
//});




//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
// Route::group(['middleware' => 'auth:api'], function(){
// 	Route::post('passport', 'Api\Admin\UserController@passport');
// });