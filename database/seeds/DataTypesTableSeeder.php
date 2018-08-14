<?php

use App\Models\DataType;
use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'users');
        if (! $dataType->exists) {
            $dataType->fill([
                'name' => 'users',
                'display_name_singular' => '用户',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-people',
                'model_name' => \App\Models\User::class,
                'policy_name' => \App\Policies\UserPolicy::class,
                'controller' => '',
                'generate_permissions' => 1,
                'description' => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'menus');
        if (! $dataType->exists) {
            $dataType->fill([
                'name' => 'users',
                'display_name_singular' => '菜单',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-people',
                'model_name' => \App\Models\Menu::class,
                'policy_name' => '',
                'controller' => '',
                'generate_permissions' => 1,
                'description' => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'roles');
        if (! $dataType->exists) {
            $dataType->fill([
                'name' => 'users',
                'display_name_singular' => '角色',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-people',
                'model_name' => \App\Models\Role::class,
                'policy_name' => '',
                'controller' => '',
                'generate_permissions' => 1,
                'description' => '',
            ])->save();
        }
    }

    protected function dataType($field, $value)
    {
        return DataType::firstOrNew([$field => $value]);
    }
}
