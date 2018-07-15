<?php

use Illuminate\Support\Facades\Route;

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
    // Route::get('/', 'DefaultsController@welcome')->name('welcome');
    Route::get('/', 'TopicsController@index')->name('welcome');

    // 用户登录登出。
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // 用户注册。
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // 忘记密码。
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    // 默认主页。
    Route::get('/home', 'HomesController@index')->name('home');

    // 用户中心。
    Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);


    // 用户话题。
    Route::resource('topics', 'TopicsController', [
        'only' => ['index', 'create', 'store', 'update', 'edit', 'destroy'],
    ]);
    Route::post('upload', 'TopicsController@upload')->name('topics.upload');
    Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

    // 话题分类。
    Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

    // 话题回复。
    Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

    // 消息通知。
    Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);
});
