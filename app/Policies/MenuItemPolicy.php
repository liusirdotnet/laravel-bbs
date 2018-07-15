<?php

namespace App\Policies;

use App\Models\DataType;
use App\Support\Contracts\UserInterface;
use App\Support\Facades\Admin;

class MenuItemPolicy extends Policy
{
    protected static $dataTypes;

    protected static $permissions;

    /**
     * @param \App\Support\Contracts\UserInterface $user
     * @param                                      $model
     * @param                                      $action
     *
     * @return bool
     */
    protected function checkPermission(UserInterface $user, $model, $action)
    {
        if (self::$permissions === null) {
            self::$permissions = Admin::getModel('Permission')->all();
        }

        if (self::$dataTypes === null) {
            self::$dataTypes = DataType::all()->keyBy('slug');
        }

        $regex = str_replace('/', '\/', preg_quote(route('admin.dashboard')));
        $slug = preg_replace('/' . $regex . '/', '', $model->link(true));
        $slug = str_replace('/', '', $slug);

        if ($str = self::$dataTypes->get($slug)) {
            $slug = $str->name;
        }

        if ($slug === '') {
            $slug = 'admin';
        }

        // If permission doesn't exist, we can't check it!
        if (! self::$permissions->contains('key', 'access_' . $slug)) {
            return true;
        }

        return $user->hasPermission('access_' . $slug);
    }
}
