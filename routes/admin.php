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

        $namespacePrefix = '\\' . config('admin.controllers.namespace') . '\\';

        // 默认路由。
        Route::get('/', 'DefaultsController@index')->name('dashboard');

        // 个人简介路由。
        Route::get('profile', 'UsersController@profile')->name('profile');

        // 用户头像上传路由。
        Route::post('upload', ['UsersController@upload'])->name('users.upload');

        try {
            foreach (\App\Models\DataType::all() as $dataType) {
                $breadController = $dataType->controller
                    ? $dataType->controller
                    : $namespacePrefix . 'AdminController';

                Route::get($dataType->slug . '/order', $breadController . '@order')->name($dataType->slug . '.order');
                Route::post($dataType->slug . '/order', $breadController . '@update_order')->name($dataType->slug
                    . '.order');
                Route::resource($dataType->slug, $breadController);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: " . $e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // 用户路由。
        Route::resource('users', 'UsersController');

        // 角色路由。
        Route::resource('roles', 'RolesController');

        // 菜单路由。
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix . 'MenusController@builder', 'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix . 'MenusController@order_item', 'as' => 'order']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix . 'MenusController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix . 'MenusController@add_item', 'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix . 'MenusController@update_item', 'as' => 'update']);
            });
        });
    });
});
