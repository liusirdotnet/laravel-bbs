<?php

namespace App\Http\Controllers\Admin;

use App\Support\Database\Schema\AbstractSchemaManager;
use App\Support\Facades\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BreadsController extends Controller
{
    public function index(Request $request)
    {
        Admin::can('access_bread');

        $dataTypes = Admin::getModel('DataType')
            ->select('id', 'name', 'slug')
            ->get()
            ->keyBy('name')
            ->toArray();
        $tables = array_map(function ($table) use ($dataTypes) {
            $table = [
                'name' => $table,
                'slug' => isset($dataTypes[$table]['slug']) ?? null,
                'dataTypeId' => isset($dataTypes[$table]['id']) ?? null,
            ];

            return (object)$table;
        }, AbstractSchemaManager::listTables());
    }

    public function create(Request $request, $table)
    {
        Admin::can('access_bread');

        $data = $this->getBreads($table);
        $data['options'] = AbstractSchemaManager::describeTable($table);

        return view('admin.breads.bread', $data);
    }

    public function store(Request $request)
    {
        try {
            /** @var \App\Models\DataType $dataType */
            $dataType = Admin::getModel('DataType');
            $result = $dataType->updateDataType($request->all(), true);
            $data = $result
                ? $this->alertSuccess('创建 Bread 成功')
                : $this->alertError('创建 Bread 失败');

            return redirect()->route('voyager.bread.index')->with($data);
        } catch (\Exception $e) {
            return redirect()->route('admin.breads.index');
        }
    }

    public function edit(Request $request, $table)
    {
        Admin::can('access_bread');

        $dataType = Admin::getModel('DataType')->whereName($table)->first();
        $options = AbstractSchemaManager::describeTable($table);
        $tables = AbstractSchemaManager::listTables();

        return view('admin.breads.bread', compact(
            'dataType',
            'options',
            'table',
            'tables'
        ));
    }

    public function update(Request $request, $id)
    {
        Admin::can('access_bread');

        try {
            /** @var \App\Models\DataType $dataType */
            $dataType = Admin::getModel('DataType')->find($id);
            $result = $dataType->updateDataType($request->all(), true);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    protected function getBreads($table)
    {
        $displayName = Str::singular(implode(' ', explode('_', Str::title($table))));
        $modelNamespace = config('admin.models.namespace', app()->getNamespace());

        return [
            'table' => $table,
            'slug' => Str::slug($table),
            'display_name' => $displayName,
            'display_name_plural' => Str::plural($displayName),
            'model_name' => $modelNamespace . Str::studly(Str::singular($table)),
            'generate_permissions' => true,
        ];
    }
}
