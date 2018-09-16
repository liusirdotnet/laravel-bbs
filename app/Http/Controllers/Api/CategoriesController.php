<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoriesController extends ApiController
{
    public function index()
    {
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }
}
