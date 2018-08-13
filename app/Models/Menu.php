<?php

namespace App\Models;

use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

class Menu extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Admin::getModelClass('MenuItem'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentItems()
    {
        return $this->hasMany(Admin::getModelClass('MenuItem'))
            ->where('parent_id', '=', 0);
    }

    /**
     * 显示菜单。
     *
     * @param string $name
     * @param null   $type
     * @param array  $options
     *
     * @return \Illuminate\Support\HtmlString|bool
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

        $options = (object) $options;

        if (\in_array(strtolower($type), ['admin', 'admin_sidebar'], true)) {
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
            $options->user = (object) compact(
                'permissions',
                'dataTypes',
                'prefix',
                'authorities'
            );

            if (starts_with($type, 'admin_')) {
                $type = \mb_substr($type, 6);
            }
        } else {
            if ($type === null) {
                $type = 'default';
            } elseif ($type === 'bootstrap' && ! \view()->exists($type)) {
                $type = 'bootstrap';
            }
        }
        $view = 'admin.partials.' . $type;

        $html = View::make($view, [
            'items' => $menu->parentItems->sortBy('order'),
            'options' => $options,
        ])->render();

        return new HtmlString($html);
    }
}
