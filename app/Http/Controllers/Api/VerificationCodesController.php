<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class VerificationCodesController extends ApiController
{
    public function store()
    {
        return $this->response->array([
            'message' =>'store verification code',
        ]);
    }
}
