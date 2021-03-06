<?php

use Dingo\Api\Routing\Router;

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
    'middleware' => ['serializer:array', 'bindings', 'locale']
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

        // 分类列表。
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        // 话题列表。
        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');

        // 用户话题列表。
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');

        // 话题详情。
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');

        // 话题回复列表。
        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.topics.replies.index');

        // 用户回复列表。
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');

        // 推荐资源列表。
        $api->get('links', 'LinksController@index')
            ->name('api.links.index');

        // 活跃用户列表。
        $api->get('users/active', 'UsersController@activeIndex')
            ->name('api.users.active.index');

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

            // 发布话题。
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');

            // 更新话题。
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');

            // 删除话题。
            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

            // 发布回复。
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');

            // 删除回复。
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.replies.destroy');

            // 通知列表。
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');

            // 通知统计。
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');

            // 通知已读。
            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read');

            // 当前用户角色。
            $api->get('user/roles', 'RolesController@index')
                ->name('api.user.roles.index');
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
