<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\Traits\TokenTrait;
use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationsController extends ApiController
{
    use TokenTrait;

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->withResponseToken($token)->setStatusCode(201);
    }

    public function update()
    {
        $token = Auth::guard('api')->refresh();

        return $this->withResponseToken($token);
    }

    public function destroy()
    {
        Auth::guard('api')->logout();

        return $this->response->noContent();
    }
}
