<?php

namespace App\Http\Controllers\Admin;

use App\Support\Facades\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MenusController extends AdminController
{
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;
        $data = \call_user_func([$dataType->model_name, 'findOrFail'], $id);

        try {
            $this->authorize('edit', $data);
        } catch (AuthorizationException $e) {
            //
        }

        $validator = $this->validateWithForm($request->all(), $dataType->editRows, $dataType->name, $id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (! $request->ajax()) {
            $this->saveData($request, $slug, $dataType->editRows, $data);

            return redirect()
                ->route('admin.' . $dataType->slug . '.index')
                ->with([
                    'message' => $dataType->display_name_singular . ' 更新成功',
                    'alert-type' => 'success',
                ]);
        }
    }

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

    public function addItem(Request $request)
    {
    }

    public function updateItem(Request $request)
    {
    }

    public function orderItem(Request $request)
    {
        $items = json_decode($request->input('order'));

        $this->orderMenuItem($items, null);
    }

    private function orderMenuItem($items, $parentId)
    {
        foreach ($items as $index => $item) {
            $item = Admin::getModel('MenuItem')->findOrFail($item->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($item->children)) {
                $this->orderMenuItem($item->children, $item->id);
            }
        }
    }
}
