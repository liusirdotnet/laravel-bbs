<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:255',
                    'password' => 'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
            case 'PATCH':
                $userId = Auth::guard('api')->id();

                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . $userId,
                    'email' => 'email',
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,' . $userId,
                ];
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信验证键',
            'verification_code' => '短信验证码',
        ];
    }

    public function messages()
    {
        return [
            'name.require' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、中划线和下划线',
            'name.between' => '用户名必须介于 3 - 25 个字符之间',
            'name.required' => '用户名不能为空',
        ];
    }
}
