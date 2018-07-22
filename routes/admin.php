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

        // 默认路由。
        Route::get('/', 'DefaultsController@index')->name('dashboard');

        // 个人简介路由。
        Route::get('profile', 'UsersController@profile')->name('profile');

        // 用户头像上传路由。
        Route::post('upload', ['UsersController@upload'])->name('users.upload');

        // 用户路由。
        Route::resource('users', 'UsersController');

        // 角色路由。
        Route::resource('roles', 'RolesController');

        // 菜单路由。
        Route::group(['prefix' => 'menus'], function () {
            Route::get('/', 'MenusController@index')->name('menus.index');
        });
    });
});
