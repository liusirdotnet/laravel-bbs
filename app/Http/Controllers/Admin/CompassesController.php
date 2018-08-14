<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompassesController extends Controller
{
    public function index(Request $request)
    {
        $active_tab = null;

        return view('admin.compasses.index', compact(
            'active_tab'
        ));
    }
}
