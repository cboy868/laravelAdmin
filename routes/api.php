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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'api\admin'], function () {
    Route::post('/login', 'UserController@login');
    Route::post('/logout', 'UserController@logout');
    // Route::post('/passport', 'UserController@passport');
});


Route::group(['middleware' => 'auth:api'], function(){
	Route::post('passport', 'Api\Admin\UserController@passport');
});