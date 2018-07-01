<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * NotificationsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        Auth::user()->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
