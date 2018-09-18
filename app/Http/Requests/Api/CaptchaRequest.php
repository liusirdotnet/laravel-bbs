<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class CaptchaRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^1[345789]\d{9}$/|unique:users'
        ];
    }
}
