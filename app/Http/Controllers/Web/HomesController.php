<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
