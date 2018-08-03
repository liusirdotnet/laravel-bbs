<?php

namespace App\Http\Controllers\Admin;

use App\Support\Facades\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class MenusController extends AdminController
{
    public function builder(Request $request, $id)
    {
        $menu = Admin::getModel('Menu')->findOrFail($id);

        try {
            $this->authorize('edit', $menu);
        } catch (AuthorizationException $e) {
            //
        }

        return view('admin.menus.builder', compact([
            'menu',
        ]));
    }
}
