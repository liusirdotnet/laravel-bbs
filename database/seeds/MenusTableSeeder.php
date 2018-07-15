<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::firstOrCreate([
            'name' => 'founder',
        ]);
        Menu::firstOrCreate([
            'name' => 'webmaster',
        ]);
        Menu::firstOrCreate([
            'name' => 'user',
        ]);
    }
}
