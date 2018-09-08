<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Database\Schema\AbstractSchemaManager;
use App\Support\Database\Schema\Identifier;
use App\Support\Database\Schema\Table;
use App\Support\Database\Types\Type;
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

    public function edit(Request $request, $table)
    {
        Admin::can('access_bread');

        if (! AbstractSchemaManager::tableExists($table)) {
            return redirect()
                ->route('admin.databases.index')
                ->with([
                    'message' => '表不存在',
                    'alert-type' => 'error',
                ]);
        }
        $db = $this->prepareDbManager('update', $table);

        return view('admin.databases.database', compact(
            'db'
        ));
    }

    private function prepareDbManager(string $action, string $table = '')
    {
        $db = new \stdClass();
        $db->types = Type::getPlatformTypes();

        if ($action === 'update') {
            $db->table = AbstractSchemaManager::listTableDetails($table);
            $db->formAction = route('admin.databases.update', $table);
        } else {
            $db->table = new Table('创建表');
            $db->table->addColumn('id', 'integer', [
                'unsigned' => true,
                'notnull' => true,
                'autoincrement' => true,
            ]);
            $db->table->setPrimaryKey(['id'], 'primary');
            $db->formAction = route('admin.databases.store');
        }
        $oldTable = old('table');
        $db->oldTable = $oldTable ? $oldTable : json_encode(null);
        $db->action = $action;
        $db->identifierRegex = Identifier::REGEX;
        $db->platform = AbstractSchemaManager::getDatabasePlatform()->getName();

        return $db;
    }
}
