<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageHandler;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use Illuminate\Http\Request;

class ImagesController extends ApiController
{
    public function store(ImageRequest $request, ImageHandler $handler, Image $image)
    {
        $user = $this->user();
        $size = $request->type === 'avatar' ? 362 : 1024;
        $result = $handler->upload($request->image, str_plural($request->type), $user->id, $size);
        $image->path = $result['path'];
        $image->type = $request->typpe;
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image, new ImageTransformer())
            ->setStatusCode(201);
    }
}
