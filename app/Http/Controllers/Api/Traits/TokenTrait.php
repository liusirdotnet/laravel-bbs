<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Support\Facades\Auth;

trait TokenTrait
{
    protected function withResponseToken(string $token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}
