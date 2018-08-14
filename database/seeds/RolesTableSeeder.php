<?php

use App\Models\Role;
use Faker\Generator;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'founder' => 'Founder',
            'webmaster' => 'Webmaster',
            'user' => 'User',
        ];
        $faker = app(Generator::class);
        $roles = factory(Role::class)
            ->times(3)
            ->make()
            ->each(function ($role, $index) use ($data, $faker) {
                $keys = array_keys($data);
                $vals = array_values($data);
                $role->name = $keys[$index];
                $role->display_name = $vals[$index];
            });
        Role::insert($roles->toArray());
    }
}
