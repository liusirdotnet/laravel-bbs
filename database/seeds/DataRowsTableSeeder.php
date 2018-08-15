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
            'name' => ['text', '名称', 1, 1, 1, 1, 1, 1, 2, '{"model":"App\\\\Models\\\\User","table":"users","type":"belongsTo","column":"role_id","key":"id","pivot_table":"roles","pivot":"0","validation":{"rule":"required|between:3,25|regex:/^[A-Za-z0-9-_]+$/|unique:users,name,{Auth::id()}","messages":{"required":"用户名 不能为空。","between":"用户名 必须介于 3 - 25 个字符之间。","regex":"用户名 只支持英文、数字、中划线和下划线。","unique":"用户名 已被占用，请重新填写。"}}}'],
            'email' => ['email', '邮箱', 1, 1, 1, 1, 1, 1, 3, '{"model":"App\\\\Models\\\\User","table":"users","type":"belongsTo","column":"role_id","key":"id","pivot_table":"roles","pivot":"0","validation":{"rule":"required|email","messages":{"required":"邮箱 不能为空。","email":"邮箱 格式不正确，请重新填写。"}}}'],
            'password' => ['password', '密码', 1, 0, 0, 1, 1, 0, 4, ''],
            'remember_token' => ['string', '记住我令牌', 0, 0, 0, 0, 0, 0, 5, ''],
            'avatar' => ['image', '头像', 0, 1, 1, 1, 1, 1, 6, ''],
            'role' => ['relationship', '角色', 0, 1, 1, 1, 1, 0, 7, '{"model":"App\\\\Models\\\\Role","table":"roles","type":"belongsTo","column":"role_id","key":"id","label":"display_name","pivot_table":"roles","pivot":"0","relationship":{"key":"id","label":"name"}}'],
            'roles' => ['relationship', '角色', 0, 0, 1, 1, 1, 0, 7, '{"model":"App\\\\Models\\\\Role","table":"roles","type":"belongsToMany","column":"id","key":"id","label":"display_name","pivot_table":"user_roles","pivot":"1","taggable":"0"}'],
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
