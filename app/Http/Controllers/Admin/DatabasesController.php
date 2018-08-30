<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabasesController extends Controller
{
    public function index(Request $request)
    {
        Admin::can('access_database');

        $dataType = Admin::getModel('DataType')
            ->select('id', 'name', 'slug')
            ->get()
            ->keyBy('name')
            ->toArray();

        $tables = array_map(function ($table) use ($dataType) {
            $table = [
                'name' => $table,
                'slug' => $dataType[$table]['slug'] ?? null,
                'dataTypeId' => $dataType[$table]['id'] ?? null,
            ];

            return (object)$table;
        }, DB::connection()->getDoctrineSchemaManager()->listTableNames());

        return view('admin.databases.index', compact(
            'tables',
            'dataType'
        ));
    }
}
