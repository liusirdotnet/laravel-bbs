<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'founder')->firstOrFail();
        $permissions = Permission::all();

        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
