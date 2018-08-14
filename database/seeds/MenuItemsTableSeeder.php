<?php

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'founder')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => '仪表盘',
            'url' => '',
            'route' => 'admin.dashboard',
        ]);
        if (! $menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-boat',
                'color' => null,
                'parent_id' => 0,
                'order' => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => '角色',
            'url' => '',
            'route' => 'admin.roles.index',
        ]);
        if (! $menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => null,
                'parent_id' => 0,
                'order' => 5,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => '用户',
            'url' => '',
            'route' => 'admin.users.index',
        ]);
        if (! $menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => null,
                'parent_id' => 0,
                'order' => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => '权限',
            'url' => '',
            'route' => 'admin.permissions.index',
        ]);
        if (! $menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => null,
                'parent_id' => 0,
                'order' => 2,
            ])->save();
        }

        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => '工具',
            'url' => '',
        ]);
        if (! $toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => null,
                'parent_id' => 0,
                'order' => 9,
            ])->save();
        }
    }
}
