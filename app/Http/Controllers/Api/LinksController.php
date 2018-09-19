<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Link;
use App\Transformers\LinkTransformer;
use Illuminate\Http\Request;

class LinksController extends ApiController
{
    public function index(Link $link)
    {
        $links = $link->getAllCaches();

        return $this->response->collection($links, new LinkTransformer());
    }
}
