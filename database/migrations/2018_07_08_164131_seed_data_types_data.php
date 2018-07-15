<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedDataTypesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dataTypes = [
            [
                'name'                  => 'users',
                'slug'                  => 'users',
                'display_name_singular' => '用户',
                'display_name_plural'   => 'Users',
                'icon'                  => 'voyager-person',
                'model_name'            => \App\Models\User::class,
                'policy_name'           => \App\Policies\UserPolicy::class,
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ],
            [
                'name'                  => 'menus',
                'slug'                  => 'menus',
                'display_name_singular' => '菜单',
                'display_name_plural'   => 'Menus',
                'icon'                  => 'voyager-person',
                'model_name'            => \App\Models\Menu::class,
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ],
            [
                'name'                  => 'roles',
                'slug'                  => 'roles',
                'display_name_singular' => '角色',
                'display_name_plural'   => 'Roles',
                'icon'                  => 'voyager-person',
                'model_name'            => \App\Models\Role::class,
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ],
            [
                'name'                  => 'categories',
                'slug'                  => 'categories',
                'display_name_singular' => '分类',
                'display_name_plural'   => 'Categories',
                'icon'                  => 'voyager-category',
                'model_name'            => \App\Models\Category::class,
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ],
            [
                'name'                  => 'topics',
                'slug'                  => 'topics',
                'display_name_singular' => '话题',
                'display_name_plural'   => 'Topics',
                'icon'                  => 'voyager-person',
                'model_name'            => \App\Models\Topic::class,
                'policy_name'           => \App\Policies\TopicPolicy::class,
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ],
        ];

        DB::table('data_types')->insert($dataTypes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('data_types')->truncate();
    }
}
