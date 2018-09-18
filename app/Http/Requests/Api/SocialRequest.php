<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class SocialRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        if ($this->social_type === 'weixin' && ! $this->code) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
