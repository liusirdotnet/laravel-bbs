<?php

use Illuminate\Database\Seeder;

class DataRowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userDataType = \App\Models\DataType::where('slug', 'users')->firstOrFail();
        $menuDataType = \App\Models\DataType::where('slug', 'menus')->firstOrFail();
        $roleDataType = \App\Models\DataType::where('slug', 'roles')->firstOrFail();

        $this->userDataRow($userDataType);
        $this->menuDataRow($menuDataType);
        $this->roleDataRow($roleDataType);
    }

    protected function userDataRow($dataType)
    {
        $values = [
            'id' => ['number', 'ID', 1, 0, 0, 0, 0, 0, 1, ''],
            'name' => ['text', '名称', 1, 1, 1, 1, 1, 1, 2, ''],
            'email' => ['email', '邮箱', 1, 1, 1, 1, 1, 1, 3, ''],
            'password' => ['email', '密码', 1, 0, 0, 1, 1, 0, 4, ''],
            'remember_token' => ['email', '记住我令牌', 0, 0, 0, 0, 0, 0, 5, ''],
            'avatar' => ['image', '头像', 0, 1, 1, 1, 1, 1, 6, ''],
            'role' => ['relationship', '角色', 0, 1, 1, 1, 1, 0, 7, '{"model":"App\\Models\\Role","table":"roles","type":"belongsTo","column":"role_id","key":"id","label":"display_name","pivot_table":"roles","pivot":"0"}'],
            'created_at' => ['timestamp', '创建时间', 0, 1, 1, 0, 0, 0, 8, ''],
            'updated_at' => ['timestamp', '更新时间', 0, 0, 0, 0, 0, 0, 9, ''],
        ];
        $this->fillData($dataType, $values);
    }

    protected function menuDataRow($dataType)
    {
        $values = [
            'id' => ['number', 'ID', 1, 0, 0, 0, 0, 0, 1, ''],
            'name' => ['text', '名称', 1, 1, 1, 1, 1, 1, 2, ''],
            'created_at' => ['timestamp', '创建时间', 0, 0, 0, 0, 0, 0, 3, ''],
            'updated_at' => ['timestamp', '更新时间', 0, 0, 0, 0, 0, 0, 4, ''],
        ];
        $this->fillData($dataType, $values);
    }

    protected function roleDataRow($dataType)
    {
        $values = [
            'id' => ['number', 'ID', 1, 0, 0, 0, 0, 0, 1, ''],
            'name' => ['text', '名称', 1, 1, 1, 1, 1, 1, 2, ''],
            'display_name' => ['text', '显示名称', 1, 1, 1, 1, 1, 1, 3, ''],
            'created_at' => ['timestamp', '创建时间', 0, 0, 0, 0, 0, 0, 4, ''],
            'updated_at' => ['timestamp', '更新时间', 0, 0, 0, 0, 0, 0, 5, ''],
        ];
        $this->fillData($dataType, $values);
    }

    protected function fillData($dataType, $data)
    {
        foreach ($data as $key => $val) {
            $dataRow = $this->dataRow($dataType, $key);

            if (! $dataRow->exists) {
                $dataRow->fill(array_combine(self::getKeys(), $val))->save();
            }
        }
    }

    protected static function getKeys()
    {
        return ['type', 'display_name', 'required', 'access', 'read', 'add', 'edit', 'delete', 'order', 'details'];
    }

    protected function dataRow($dataType, $field)
    {
        return \App\Models\DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field' => $field,
        ]);
    }
}
