<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Support\Facades\Admin;

class RolesController extends AdminController
{
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        try {
            $this->authorize('add', app($dataType->model_name));
        } catch (AuthorizationException $e) {
            //
        }

        if (! $request->ajax()) {
            $model = new $dataType->model_name();
            $this->saveData($request, $slug, $dataType->addRows, $model);
            $model->permissions()->sync($request->input('permissions', []));

            return redirect()
                ->route("admin.{$dataType->slug}.index")
                ->with([
                    'message'    => __('添加成功') . " {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }

    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        try {
            $this->authorize('edit', app($dataType->model_name));
        } catch (AuthorizationException $e) {
            //
        }

        $validator = $this->validateWithForm($request->all(), $dataType->editRows);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (! $request->ajax()) {
            $data = \call_user_func([$dataType->model_name, 'findOrFail'], $id);
            $this->saveData($request, $slug, $dataType->editRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
                ->route("admin.{$slug}.index")
                ->with([
                    'message'    => $dataType->display_name_singular . ' 更新成功',
                    'alert-type' => 'success',
                ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        try {
            $this->authorize('delete', app($dataType->model_name));
        } catch (AuthorizationException $e) {
            //
        }

        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $val) {
            $data = \call_user_func([$dataType->model_name, 'findOrFail'], $val);
            $this->cleanup($dataType, $data);
        }

        $displayName = \count($ids) > 1 ? $dataType->display_name_plural : $dataType->display_name_singular;

        $res = $data->destroy($ids);
        $data = $res
            ? ['message' => '删除成功' . " {$displayName}", 'alert-type' => 'success',]
            : ['message' => '删除错误' . " {$displayName}", 'alert-type' => 'error',];

        return redirect()
            ->route("admin.{$dataType->slug}.index")
            ->with($data);
    }
}
