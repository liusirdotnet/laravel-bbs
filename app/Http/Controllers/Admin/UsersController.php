<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\RelationshipParserTrait;
use App\Models\User;
use App\Support\Facades\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersController extends AdminController
{
    /**
     * 用户创建操作。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
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
        $validator = $this->validateWithForm($request->all(), $dataType->addRows);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (! $request->has('_validate')) {
            $data = $this->saveData($request, $slug, $dataType->addRows, new $dataType->model_name());

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            return redirect()
                ->route("admin.{$dataType->slug}.index")
                ->with([
                    'message'    => $dataType->display_name_singular . '添加成功',
                    'alert-type' => 'success',
                ]);
        }
    }

    /**
     * 用户更新操作。
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
                    'message'    => $dataType->display_name_singular . ' 更新成功',
                    'alert-type' => 'success',
                ]);
        }
    }

    /**
     * 用户删除操作。
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        try {
            $this->authorize('delete', app($dataType->model_class));
        } catch (AuthorizationException $e) {
            //
        }

        $ids = [];
        if (empty($id)) {
            $ids = explode(',', $request->ids);
        } else {
            $ids[] = $id;
        }
        foreach ($ids as $val) {
            $model = \call_user_func([$dataType->model_name, 'findOrFail'], $val);
            $this->cleanup($dataType, $model);
        }

        $displayName = \count($ids) > 1 ? $dataType->display_name_plural : $dataType->display_name_singular;
        $result = $model->destroy($ids);
        $data = $result
            ? [
                'message'    => "{$displayName} " . '删除成功！',
                'alert-type' => 'success',
            ]
            : [
                'message'    => "{$displayName} " . '删除失败！',
                'alert-type' => 'error',
            ];

        return redirect()
            ->route("admin.{$dataType->slug}.index")
            ->with($data);
    }

    public function profile(Request $request)
    {
        dd($request->all());
    }
}
