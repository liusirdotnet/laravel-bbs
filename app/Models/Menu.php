<?php

namespace App\Models;

use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

class Menu extends Model
{
    protected $table = 'menus';

    public function items()
    {
        return $this->hasMany(Admin::getModelClass('MenuItem'));
    }

    public function parentItems()
    {
        return $this->hasMany(Admin::getModelClass('MenuItem'))
            ->whereNull('parent_id');
    }

    /**
     * 显示菜单。
     *
     * @param string $name
     * @param null   $type
     * @param array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public static function display($name, $type = null, array $options = [])
    {
        $menu = static::where('name', '=', $name)
            ->with([
                'parentItems.children' => function ($query) {
                    $query->orderBy('order');
                },
            ])
            ->first();

        if ($menu === null) {
            return false;
        }

        $options = (object)$options;

        if (\in_array(strtolower($type), ['admin', 'admin_menu'], true)) {
            $permissions = Admin::getModel('Permission')->all();
            $dataTypes = Admin::getModel('DataType')->all();
            $prefix = trim(route('admin.dashboard', [], false), '/');

            $authorities = null;

            if (! Auth::guest()) {
                $user = Admin::getModel('User')->find(Auth::id());
                $authorities = $user->role
                    ? $user->role->permissions->pluck('key')->toArray()
                    : [];
            }
            $options->user = (object)compact(
                'permissions',
                'dataTypes',
                'prefix',
                'authorities'
            );

            if (starts_with($type, 'admin_')) {
                $type = \mb_substr($type, 6);
            }
            $type = 'admin.menus.' . $type;
        }

        $html = View::make($type, [
            'items'   => $menu->parentItems->sortBy('order'),
            'options' => $options,
        ])->render();

        return new HtmlString($html);
    }
}
