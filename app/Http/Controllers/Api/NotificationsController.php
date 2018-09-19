<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends ApiController
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }
}
