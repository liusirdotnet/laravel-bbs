<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\RelationshipParserTrait;
use App\Support\Facades\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersController extends AbstractController
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
        $search = (object)[
            'key'    => $request->get('key'),
            'value'  => $request->get('s'),
            'filter' => $request->get('filter'),
        ];
        $fields = ['ID', '用户名', '邮箱', '简介', '通知数', '头像', '创建时间', '更新时间',];
        $columns = Schema::getColumnListing('users');
        $columns = array_flip($columns);
        unset($columns['role_id'], $columns['password'], $columns['remember_token']);
        $searchable = array_combine(array_flip($columns), $fields);

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

    public function profile(Request $request)
    {
        dd($request->all());
    }
}
