<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UsersController extends ApiController
{
    /**
     * 用户注册。
     *
     * @param \App\Http\Requests\Api\UserRequest $request
     *
     * @return void
     */
    public function store(UserRequest $request)
    {
        $data = Cache::get($request->verification_key);

        if (!$data) {
            return $this->response->error('验证码已失效', 422);
        }

        // hash_equals() 函数可以有效的防止时序攻击。
        if (!hash_equals($data['code'], $request->verification_code)) {
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $data['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存。
        Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
