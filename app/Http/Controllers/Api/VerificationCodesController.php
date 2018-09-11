<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends ApiController
{
    /**
     * 发送手机验证码。
     *
     * @param \App\Http\Requests\Api\VerificationCodeRequest $request
     * @param \Overtrue\EasySms\EasySms $easySms
     *
     * @return void
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        try {
            $easySms->send(18810142522, [
                'content'  => "【小禾社区】您的验证码是{$code}。如非本人操作，请忽略本短信",
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            $message = $e->getException('yunpian')->getMessage();

            return $this->response->errorInternal($message ?? '短信发送异常');
        }
        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(10);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'message' => '您的验证码',
        ])->setStatusCode(201);
    }
}
