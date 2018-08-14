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

        // 仪表盘。
        MenuItem::firstOrCreate([
            'parent_id' => 0,
            'menu_id' => $menu->id,
            'title' => '仪表盘',
            'url' => '',
            'route' => 'admin.dashboard',
            'icon_class' => 'voyager-dashboard',
            'color' => '',
            'order' => 1,
        ]);

        // 权限管理。
        $menuItem = MenuItem::firstOrCreate([
            'parent_id' => 0,
            'menu_id' => $menu->id,
            'title' => '权限管理',
            'url' => '',
            'route' => '',
            'icon_class' => 'voyager-dashboard',
            'color' => '',
            'order' => 2,
        ]);

        $item = MenuItem::firstOrNew([
            'parent_id' => $menuItem->id,
            'menu_id' => $menu->id,
            'title' => '菜单',
            'url' => '',
            'route' => 'admin.menus.index',
            'icon_class' => 'voyager-lifebuoy',
        ]);
        if (! $item->exists) {
            $item->fill(['target' => '_self', 'color' => '', 'order' => 3,])->save();
        }

        $item = MenuItem::firstOrNew([
            'parent_id' => $menuItem->id,
            'menu_id' => $menu->id,
            'title' => '角色',
            'url' => '',
            'route' => 'admin.roles.index',
            'icon_class' => 'voyager-lock',
        ]);
        if (! $item->exists) {
            $item->fill(['target' => '_self', 'color' => null, 'order' => 4,])->save();
        }

        $item = MenuItem::firstOrNew([
            'parent_id' => $menuItem->id,
            'menu_id' => $menu->id,
            'title' => '用户',
            'url' => '',
            'route' => 'admin.users.index',
            'icon_class' => 'voyager-people',
        ]);
        if (! $item->exists) {
            $item->fill(['target' => '_self', 'color' => null, 'order' => 5,])->save();
        }


        $menuItem = MenuItem::firstOrCreate([
            'parent_id' => 0,
            'menu_id' => $menu->id,
            'title' => '系统设置',
            'url' => '',
            'route' => '',
            'icon_class' => 'voyager-settings',
            'color' => '',
            'order' => 6,
        ]);

        $item = MenuItem::firstOrNew([
            'parent_id' => $menuItem->id,
            'menu_id' => $menu->id,
            'title' => '用户',
            'url' => '',
            'route' => 'admin.users.index',
            'icon_class' => 'voyager-people',
        ]);
        if (! $item->exists) {
            $item->fill(['target' => '_self', 'color' => null, 'order' => 7,])->save();
        }
    }
}
