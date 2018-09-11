<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends ApiController
{
    /**
     * 发送短信验证码。
     *
     * @param \App\Http\Requests\Api\VerificationCodeRequest $request
     * @param \Overtrue\EasySms\EasySms $easySms
     *
     * @return void
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $data = Cache::get($request->captcha_key);

        if (! $data) {
            return $this->response->error('图片验证码已失效', 422);
        }

        if (! hash_equals($data['code'], $request->captcha_code)) {
            // 验证码错误就清除缓存。
            Cache::forget($request->captcha_key);

            return $this->response->errorUnauthorized('验证码错误');
        }

        if (! app()->environment('production')) {
            $code = '3333';
        } else {
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $easySms->send($data['phone'], [
                    'content' => "【小禾社区】您的验证码是{$code}。如非本人操作，请忽略本短信",
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $result = json_encode($response->getBody()->getContents(), true);

                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
            }
        }
        $key = 'verificationCode-' . str_random(15);
        $expiredAt = now()->addMinutes(10);

        // 缓存验证码。
        Cache::put($key, ['phone' => $data['phone'], 'code' => $code], $expiredAt);
        Cache::forget($request->captcha_key);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
