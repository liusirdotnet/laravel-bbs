<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Support\Facades\Admin;
use App\Http\Controllers\Admin\Traits\RelationshipParserTrait;
use Illuminate\Support\Facades\Schema;

class RolesController extends AbstractController
{
    use RelationshipParserTrait;

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
        // $fields = ['ID', '名称', '显示名称', '创建时间', '更新时间',];
        // $searchable = array_combine(Schema::getColumnListing('roles'), $fields);
        $searchable = Schema::getColumnListing('roles');

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
            // If Model doesn't exist, get data from table name.
            $dataTypeContent = \call_user_func([\DB::table($dataType->name), $getter]);
        }

        return view('admin.roles.index', compact(
            'dataType',
            'dataTypeContent',
            'search',
            'searchable',
            'orderBy',
            'orderType'
        ));
    }

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Admin::getModel('DataType')
            ->where('slug', '=', $slug)
            ->first();

        $relationship = $this->getRelationships($dataType);
        $dataTypeContent = $dataType->model_name !== null
            ? \call_user_func([app($dataType->model_name)->with($relationship), 'findOrFail'], $id)
            : DB::table($dataType->name)->where('id', $id)->first();

        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        $this->removeRelationshipField($dataType, 'read');

        try {
            $this->authorize('read', $dataTypeContent);
        } catch (AuthorizationException $e) {
            //
        }

        return view('admin.roles.detail', compact(
            'dataType',
            'dataTypeContent'
        ));
    }

    public function create(Request $request)
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

        $dataTypeContent = $dataType->model_name !== null
            ? new $dataType->model_name()
            : false;

        foreach ($dataType->addRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->addRows[$key]['col_width'] = isset($details->width) ?? 100;
        }
        $this->removeRelationshipField($dataType, 'add');

        return view('admin.roles.create', compact(
            'dataType',
            'dataTypeContent'
        ));
    }

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
            $result = $this->saveData($request, $slug, $dataType->addRows, $model);
            $model->permissions()->sync($request->input('permissions', []));

            return redirect()
                ->route("admin.{$dataType->slug}.index")
                ->with([
                    'message'    => __('添加成功') . " {$dataType->display_name_singular}",
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
