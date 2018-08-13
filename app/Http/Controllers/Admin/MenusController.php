<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Support\Facades\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MenusController extends AdminController
{
    /**
     * 更新菜单操作。
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * 生成菜单项页面。
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * 创建菜单项操作。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeItem(Request $request)
    {
        $menu = Admin::getModel('Menu');

        try {
            $this->authorize('add', $menu);
        } catch (AuthorizationException $e) {
            //
        }

        $data = $this->prepareParameters($request->all());
        unset($data['id']);
        $data['order'] = Admin::getModel('MenuItem')->highestOrderMenuItem();

        Admin::getModel('MenuItem')->create($data);

        return redirect()
            ->route('admin.menus.builder', [$data['menu_id']])
            ->with([
                'message' => '菜单项创建成功！',
                'alert-type' => 'success',
            ]);
    }

    /**
     * 更新菜单项操作。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(Request $request)
    {
        $id = $request->input('id');
        $data = $this->prepareParameters($request->except(['id']));
        $item = Admin::getModel('MenuItem')->findOrFail($id);

        try {
            $this->authorize('edit', $item->menu);
        } catch (AuthorizationException $e) {
            //
        }
        $item->update($data);

        return redirect()
            ->route('admin.menus.builder', [$item->menu_id])
            ->with([
                'message' => '菜单项更新成功！',
                'alert-type' => 'success',
            ]);
    }

    /**
     * 删除菜单项操作。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Menu         $menu
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMenu(Request $request, Menu $menu, $id)
    {
        $item = Admin::getModel('MenuItem')->findOrFail($id);

        try {
            $this->authorize('delete', $item->menu);
        } catch (AuthorizationException $e) {
            //
        }
        $item->destroy($id);

        return redirect()
            ->route('admin.menus.builder', [$menu])
            ->with([
                'message' => '菜单项删除成功！',
                'alert-type' => 'success',
            ]);

    }

    /**
     * 排序菜单项操作。
     *
     * @param \Illuminate\Http\Request $request
     */
    public function orderItem(Request $request)
    {
        $items = json_decode($request->input('order'));

        $this->orderMenuItem($items);
    }

    private function prepareParameters($parameters)
    {
        if (array_get($parameters, 'type') === 'route') {
            $parameters['url'] = null;
        } else {
            $parameters['route'] = null;
            $parameters['parameters'] = '';
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }

    private function orderMenuItem(array $items, $parentId = 0)
    {
        foreach ($items as $index => $item) {
            $menuItem = Admin::getModel('MenuItem')->findOrFail($item->id);
            $menuItem->order = $index + 1;
            $menuItem->parent_id = $parentId;
            $menuItem->save();

            if (isset($item->children)) {
                $this->orderMenuItem($item->children, $item->id);
            }
        }
    }
}
