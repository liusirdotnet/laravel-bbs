<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nodes = [
            'access_admin',
            'access_bread',
            'access_database',
            'access_media',
            'access_compass',
        ];

        $faker = app(\Faker\Generator::class);
        $permissions = factory(Permission::class)
            ->times(5)
            ->make()
            ->each(function ($permission, $index) use ($faker, $nodes) {
                $permission->action = $nodes[$index];
            });
        Permission::insert($permissions->toArray());

        Permission::generate('menus');
        Permission::generate('roles');
        Permission::generate('users');
        Permission::generate('topics');
        Permission::generate('replies');
    }
}
