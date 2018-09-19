<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;

class RolesController extends ApiController
{
    public function index()
    {
        $roles = $this->user()->getAllRoles();

        return $this->response->collection($roles, new RoleTransformer());
    }
}
