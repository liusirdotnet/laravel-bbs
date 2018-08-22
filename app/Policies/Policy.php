<?php

namespace App\Policies;

use App\Support\Contracts\UserInterface;
use App\Support\Facades\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    /**
     * @var array
     */
    protected static $dataTypes = [];

    /**
     * Policy constructor.
     */
    public function __construct()
    {
        //
    }

    public function __call($name, $arguments)
    {
        if (\count($arguments) < 2) {
            throw new \InvalidArgumentException('Not enough arguments.');
        }

        /** @var \App\Support\Contracts\UserInterface $user */
        [0 => $user, 1 => $model] = $arguments;

        return $this->checkPermission($user, $model, $name);
    }

    public function before($user, $ability)
    {
        if ($user->hasRole('founder') || $user->hasRole('webmaster')) {
            return true;
        }
    }

    protected function checkPermission(UserInterface $user, $model, $action)
    {
        if (! isset(self::$dataTypes[\get_class($model)])) {
            $dataType = Admin::getModel('DataType');
            self::$dataTypes[\get_class($model)] = $dataType->where('model_name', \get_class($model))->first();
        }
        $dataType = self::$dataTypes[\get_class($model)];

        return $user->hasPermission($action . '_' . $dataType->name);
    }
}
