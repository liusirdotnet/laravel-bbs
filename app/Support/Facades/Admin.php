<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Foundation\Application|mixed getModel($name)
 * @method static string getModelClass($name)
 * @method static void getWidgets()
 * @method static array getAlerts()
 * @method static array getActions()
 * @method static void addFormField()
 * @method static void getFormField()
 * @method static string getVersion()
 */
class Admin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin';
    }
}
