<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiController extends BaseController
{
    use Helpers;

    public function errorResponse($statusCode, $message = null, $code = 0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
