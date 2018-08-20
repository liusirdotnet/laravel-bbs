<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomesController extends Controller
{
    /**
     * HomesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 默认主页。
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('web.homes.home');
    }
}
