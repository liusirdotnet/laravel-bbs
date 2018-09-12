<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\Traits\TokenTrait;
use App\Http\Requests\Api\SocialRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialsController extends ApiController
{
    use TokenTrait;

    public function store(SocialRequest $request, $type)
    {
        $type = strtolower($type);

        if (! in_array($type, ['weixin'], true)) {
            return $this->response->errorBadRequest();
        }

        $dirver = Socialite::driver($type);

        try {
            if ($code = $request->code) {
                $response = $dirver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type === 'weixin') {
                    $dirver->setOpenId($request->openid);
                }
            }

            $oauth = $dirver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauth->offsetExists('unionid') ? $oauth->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauth->getId())->first();
                }

                if (! $user) {
                    $user = User::create([
                        'name' => $oauth->getNickname(),
                        'avatar' => $oauth->getAvatar(),
                        'weixin_openid' => $oauth->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
                break;
        }
        $token = Auth::guard('api')->fromUser($user);

        return $this->withResponseToken($token)->setStatusCode(201);
    }
}
