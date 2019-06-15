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
], function ($router) {


    $router->post('/login', 'Admin\AuthController@login')->name('login');
    $router->post('/me', 'Admin\AuthController@me')->name('me');//个人信息
    $router->post('/logout', 'Admin\AuthController@logout')->name('logout');//登出

    /**
     * permission
     */
    Route::apiResource('roles', 'Admin\V1\Permission\RoleController');
    Route::apiResource('permissions', 'Admin\V1\Permission\PermissionController', ['except'=>['show']]);
    Route::apiResource('role-users', 'Admin\V1\Permission\RoleUsersController', ['except'=>['index','update', 'destroy']]);
    Route::post('role-users-destroy', 'Admin\V1\Permission\RoleUsersController@destroy')->name('role-users.destroy');
    Route::apiResource('role-permissions', 'Admin\V1\Permission\RolePermissionsController', ['except'=>['index','update', 'destroy']]);
    Route::post('role-permissions-destroy', 'Admin\V1\Permission\RolePermissionsController@destroy')->name('role-permissions.destroy');

    /**
     * 商户
     */
    Route::apiResource('store', 'Admin\V1\Store\StoreController');
    Route::apiResource('store-level', 'Admin\V1\Store\LevelController');
    Route::apiResource('store-cost', 'Admin\V1\Store\CostController');

    /**
     * 商品
     */
    Route::apiResource('product-brand', 'Admin\V1\Product\BrandController');
    Route::apiResource('product-category', 'Admin\V1\Product\CategoryController');
    Route::apiResource('product-sku', 'Admin\V1\Product\SkuController');
    Route::apiResource('product', 'Admin\V1\Product\ProductController');
    Route::apiResource('product-attribute-type', 'Admin\V1\Product\AttributeCategoryController');
    Route::apiResource('product-attribute-key', 'Admin\V1\Product\AttributeKeyController');
    Route::apiResource('product-attribute-value', 'Admin\V1\Product\AttributeValueController');
    Route::apiResource('product-attribute-rel', 'Admin\V1\Product\AttributeRelController');


//    Route::middleware(['auth:admin', 'auth.token'])->group(function ($router){


    Route::apiResource('pictures-category', 'Cms\PicturesCategoryController');
    Route::apiResource('pictures-user', 'Cms\PicturesUserController');
    Route::apiResource('pictures', 'Admin\V1\Cms\PicturesController');
    Route::apiResource('focus', 'Admin\V1\Cms\FocusController');
    Route::apiResource('pictures-category', 'Cms\PicturesCategoryController');
    Route::apiResource('pictures-user', 'Cms\PicturesUserController');
    Route::apiResource('pictures', 'Admin\V1\Cms\PicturesController');
    Route::apiResource('focus', 'Admin\V1\Cms\FocusController');
    Route::post('upfocus', 'Admin\V1\Cms\FocusController@upload')->name('focus.upfocus');
    Route::post('upimages', 'Admin\V1\Cms\PicturesController@upImages')->name('pictures.upimages');
    Route::post('cover', 'Admin\V1\Cms\PicturesController@cover')->name('pictures.cover');
    Route::post('edit-focus/{id}', 'Admin\V1\Cms\FocusController@updateItem')->name('focus.edit-focus');
    Route::post('delete-focus/{id}', 'Admin\V1\Cms\FocusController@deleteItem')->name('focus.delete-focus');

    Route::post('picture-delete-img/{id}', 'Admin\V1\Cms\PicturesController@deleteImg')->name('pictures.delete_img');

    Route::apiResource('cartoons', 'Admin\V1\Cms\CartoonController');

    Route::get('picture_images/{id}', 'Admin\V1\Cms\PicturesController@images')->name('pictures.images');


    Route::apiResource('brands', 'Goods\BrandController');
    Route::get('cartoon_chapters/{id}', 'Admin\V1\Cms\CartoonController@chapters')->name('cartoon.chapters');

    //商品
    Route::apiResource('brands', 'Goods\BrandController');
    Route::apiResource('types', 'Goods\TypeController');
    Route::apiResource('goods-categorys', 'Goods\CategoryController');
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