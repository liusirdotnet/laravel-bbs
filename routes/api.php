<?php

use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$api = app(Router::class);

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
], function ($api) {
    $api->get('version', function () {
        return response('this is version 1.');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 发送短信验证码。
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        // 用户注册。
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        // 生成图片验证码。
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        // 第三方登录。
        $api->post('socials/{social_type}/authorizations', 'SocialsController@store')
            ->name('api.socials.store');

        // 用户登录。
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        // 刷新令牌。
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');

        // 删除令牌。
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.delete');

        // 分类。
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 更新用户信息。
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');

            // 当前登录用户信息。
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

            // 图片资源。
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');
        });
    });
});

$api->version('v2', function ($api) {
    $api->get('version', function () {
        return response('this is version 2.');
    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
