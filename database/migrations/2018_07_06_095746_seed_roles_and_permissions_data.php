<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存。
        app()['cache']->forget('spatie.permission.cache');

        // 创建权限。
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_settings']);

        // 创建站长角色，并赋予权限。
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('manage_settings');

        // 创建网管角色，并赋予权限。
        $webmaster = Role::create(['name' => 'Webmaster']);
        $webmaster->givePermissionTo('manage_contents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清除缓存。
        app()['cache']->forget('spatie.permission.cache');

        // 清除权限相关数据表。
        DB::table(config('permission.table_names.model_has_permissions'))->delete();
        DB::table(config('permission.table_names.model_has_roles'))->delete();
        DB::table(config('permission.table_names.permissions'))->delete();
        DB::table(config('permission.table_names.roles'))->delete();
        DB::table(config('permission.table_names.role_has_permissions'));

        Model::reguard();
    }
}
