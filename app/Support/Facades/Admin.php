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
 * @method static formField($row, $dataType, $dataTypeContent)
 * @method static \Illuminate\Support\Collection formFields()
 * @method static \Illuminate\Support\Collection afterFormFields($row, $dataType, $dataTypeContent)
 * @method static void getFormField()
 * @method static string getVersion()
 * @method static bool can($action)
 * @method static bool canOrFail($permission)
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
