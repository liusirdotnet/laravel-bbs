<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Web'], function () {

    // 默认欢迎页。
    Route::get('/', 'DefaultsController@welcome')->name('welcome');

    Auth::routes();

    // 默认主页。
    Route::get('/home', 'HomesController@index')->name('home');
});
