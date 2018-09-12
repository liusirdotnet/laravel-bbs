<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\SocialRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialsController extends ApiController
{
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

            $auth = $dirver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $auth->offsetExists('unionid') ? $auth->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $auth->getId())->first();
                }

                if (! $user) {
                    $user = User::create([
                        'name' => $auth->getNickname(),
                        'avatar' => $auth->getAvatar(),
                        'weixin_openid' => $auth->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
                break;
        }

        return $this->response->array([
            'token' => $user->id,
        ]);
    }
}
