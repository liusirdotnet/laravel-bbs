<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::group(['middleware' => 'admin'], function () {

        $nsPrefix = '\\' . config('admin.controllers.namespace') . '\\';

        // 默认路由。
        Route::get('/', 'DefaultsController@index')->name('dashboard');

        // 个人简介路由。
        Route::get('profile', 'UsersController@profile')->name('profile');

        // 用户头像上传路由。
        Route::post('upload', ['UsersController@upload'])->name('users.upload');

        // try {
        //     foreach (\App\Models\DataType::all() as $dataType) {
        //         $breadController = $dataType->controller ?: $namespacePrefix . 'AdminController';
        //
        //         Route::get($dataType->slug . '/order', $breadController . '@order')->name($dataType->slug . '.order');
        //         Route::post($dataType->slug . '/order', $breadController . '@update_order')->name($dataType->slug
        //             . '.order');
        //         Route::resource($dataType->slug, $breadController);
        //     }
        // } catch (\InvalidArgumentException $e) {
        //     throw new \InvalidArgumentException("Custom routes hasn't been configured because: " . $e->getMessage(), 1);
        // } catch (\Exception $e) {
        //     // do nothing, might just be because table not yet migrated.
        // }

        // 用户路由。
        Route::resource('users', 'UsersController');

        // 角色路由。
        Route::resource('roles', 'RolesController');

        // 分类路由。
        Route::resource('categories', 'CategoriesController');

        // 菜单路由。
        Route::resource('menus', 'MenusController');

        // 菜单项路由。
        Route::group([
            'as' => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($nsPrefix) {
            Route::get('builder', ['uses' => $nsPrefix . 'MenusController@builder', 'as' => 'builder']);
            Route::post('order', ['uses' => $nsPrefix . 'MenusController@orderItem', 'as' => 'order']);

            Route::group([
                'as' => 'item.',
                'prefix' => 'item',
            ], function () use ($nsPrefix) {
                Route::delete('{id}', ['uses' => $nsPrefix . 'MenusController@destroyMenu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $nsPrefix . 'MenusController@storeItem', 'as' => 'store']);
                Route::put('/', ['uses' => $nsPrefix . 'MenusController@updateItem', 'as' => 'update']);
            });
        });

        // 面包屑路由。
        Route::group([
            'as' => 'breads.',
            'prefix' => 'breads',
        ], function () use ($nsPrefix) {
            Route::get('/', ['uses' => $nsPrefix . 'BreadsController@index']);
            Route::get('{table}/create', ['uses' => $nsPrefix . 'BreadsController@create', 'as' => 'create']);
            Route::get('{table}/edit', ['uses' => $nsPrefix . 'BreadsController@edit', 'as' => 'edit']);
        });

        // 指南路由。
        Route::group([
            'as' => 'compasses.',
            'prefix' => 'compasses',
        ], function () use ($nsPrefix) {
            Route::get('/', ['uses' => $nsPrefix . 'CompassesController@index', 'as' => 'index']);
            Route::post('/', ['uses' => $nsPrefix . 'CompassesController@index', 'as' => 'command']);
        });

        // 数据库路由。
        Route::resource('databases', 'DatabasesController');
    });
});
