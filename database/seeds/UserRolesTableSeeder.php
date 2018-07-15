<?php

use App\Support\Facades\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Admin::getModel('Role')->firstOrNew([
            'name' => 'founder',
        ]);

        if (! $role->exists) {
            $role->fill(['display_name' => 'Founder'])->save();
        }

        $permissions = Admin::getModel('Permission')->all();

        // Assign all permissions to the admin role.
        $role->permissions()->sync($permissions->pluck('id')->all());

        DB::table('user_roles')->insert(['user_id' => 1, 'role_id' => $role->id]);
    }
}
