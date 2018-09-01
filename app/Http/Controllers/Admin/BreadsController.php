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
    }

    public function create(Request $request, $table)
    {
        Admin::can('access_bread');

        $data = $this->getBreads($table);
        $data['options'] = AbstractSchemaManager::describeTable($table);

        return view('admin.breads.bread', $data);
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
