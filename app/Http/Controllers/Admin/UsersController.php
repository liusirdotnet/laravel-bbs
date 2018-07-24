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

class UsersController extends AbstractController
{
    use RelationshipParserTrait;

    /**
     * 用户列表页面。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        try {
            $this->authorize('access', app($dataType->model_name));
        } catch (AuthorizationException $e) {
            //
        }

        $getter = 'paginate';
        $orderBy = strtolower($request->get('order_by'));
        $orderType = $request->get('order_type');
        $search = (object) [
            'key'    => $request->get('key'),
            'value'  => $request->get('s'),
            'filter' => $request->get('filter'),
        ];
        $searchable = Schema::getColumnListing('users');

        if ($dataType->model_name !== null) {
            $relationships = $this->getRelationships($dataType);

            $model = app($dataType->model_name);
            $query = $model::select('*')->with($relationships);
            $this->removeRelationshipField($dataType, 'access');

            if ($search->key && $search->value && $search->filter) {
                $filter = $search->filter === 'equals' ? '=' : 'LIKE';
                $value = $search->filter === 'equals' ? $search->value : '%' . $search->value . '%';
                $query->where($search->key, $filter, $value);
            }

            if ($orderBy && \in_array($orderBy, $dataType->fields, true)) {
                $orderType = $orderType ?: 'DESC';
                $dataTypeContent = \call_user_func([$query->orderBy($orderBy, $orderType), $getter]);
            } elseif ($model->timestamps) {
                $dataTypeContent = \call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = \call_user_func([$query->orderBy($model->getKeyName(), 'DESC')], $getter);
            }
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            $dataTypeContent = \call_user_func([DB::table($dataType->name), $getter]);
        }

        return view('admin.users.index', compact(
            'dataType',
            'dataTypeContent',
            'search',
            'searchable',
            'orderBy',
            'orderType'
        ));
    }

    /**
     * 用户创建页面。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')->where('slug', '=', $slug)->first();

        try {
            $this->authorize('add', app($dataType->model_name));
        } catch (AuthorizationException $e) {
            //
        }

        $dataTypeContent = $dataType->model_name !== null ? new $dataType->model_name() : false;
        $this->removeRelationshipField($dataType, 'add');

        return view('admin.users.user', compact(
            'dataType',
            'dataTypeContent'
        ));
    }

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
     * 用户编辑页面。
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        $relationships = $this->getRelationships($dataType);
        $dataTypeContent = $dataType->model_name !== null
            ? app($dataType->model_name)->with($relationships)->findOrFail($id)
            : DB::table($dataType->name)->where('id', $id)->first();

        foreach ($dataType->editRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->editRows[$key]['col_width'] = $details->width ?? 100;
        }
        $this->removeRelationshipField($dataType, 'edit');

        try {
            $this->authorize('edit', $dataTypeContent);
        } catch (AuthorizationException $e) {
            //
        }

        return view('admin.users.user', compact(
            'dataType',
            'dataTypeContent'
        ));
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
