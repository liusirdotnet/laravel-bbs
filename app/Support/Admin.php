<?php

namespace App\Support;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\DataRow;
use App\Models\DataType;
use App\Models\MenuItem;
use App\Models\Permission;
use App\Events\Admin\AlertEvent;
use App\Support\Actions\DeleteAction;
use App\Support\Actions\EditAction;
use App\Support\Actions\ViewAction;
use App\Support\Contracts\Forms\Fields\FieldInterface;
use Arrilot\Widgets\Facade as Widget;

class Admin
{
    protected $version = 'v.0.1';

    protected $alerts = [];

    /**
     * @var array
     */
    protected $actions = [
        DeleteAction::class,
        EditAction::class,
        ViewAction::class,
    ];

    protected $formFields = [];

    protected $alertsCollected = false;

    /**
     * @var array
     */
    protected $models
        = [
            'User'       => User::class,
            'Role'       => Role::class,
            'Permission' => Permission::class,
            'DataRow'    => DataRow::class,
            'DataType'   => DataType::class,
            'Topic'      => Topic::class,
            'Menu'       => Menu::class,
            'MenuItem'   => MenuItem::class,
        ];

    public function getModel($name)
    {
        return app($this->models[studly_case($name)]);
    }

    public function getModelClass($name)
    {
        return $this->models[$name];
    }

    public function getWidgets()
    {
        $classes = config('admin.dashboard.widgets');
        $groups = Widget::group('admin::dimmers');

        foreach ($classes as $class) {
            $widget = app($class);

            if ($class === 'App\\Widgets\\Admin\\TopicWidget') {
                $widget->shouldBeDisplayed();
            }
            if ($widget->shouldBeDisplayed()) {
                $groups->addWidget($class);
            }
        }

        return $groups;
    }

    public function getAlerts()
    {
        if (! $this->alertsCollected) {
            event(new AlertEvent($this->alerts));

            $this->alertsCollected = true;
        }

        return $this->alerts;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function addFormField($formField)
    {
        if (! $formField instanceof FieldInterface) {
            $instance = app($formField);
        }

        $this->formFields[$instance->getCodeName()] = $instance;

        return $this;
    }

    public function formField($row, $dataType, $dataTypeContent)
    {
        $formField = $this->formFields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent);
    }

    public function formFields()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver", 'mysql');

        return collect($this->formFields)->filter(function ($after) use ($driver) {
            return $after->supports($driver);
        });
    }

    public function getVersion()
    {
        return $this->version;
    }

    // public function can($permission)
    // {
    //     $this->loadPermissions();
    //
    //     // Check if permission exist
    //     $exist = $this->permissions->where('key', $permission)->first();
    //
    //     // Permission not found
    //     if (! $exist) {
    //         throw new \Exception('Permission does not exist', 400);
    //     }
    //
    //     $user = $this->getUser();
    //     if ($user === null || ! $user->hasPermission($permission)) {
    //         return false;
    //     }
    //
    //     return true;
    // }
    //
    // protected function loadPermissions()
    // {
    //     if (! $this->permissionsLoaded) {
    //         $this->permissionsLoaded = true;
    //
    //         $this->permissions = self::model('Permission')->all();
    //     }
    // }
    //
    // protected function getUser($id = null)
    // {
    //     if (is_null($id)) {
    //         $id = auth()->check() ? auth()->user()->id : null;
    //     }
    //
    //     if (is_null($id)) {
    //         return;
    //     }
    //
    //     if (! isset($this->users[$id])) {
    //         $this->users[$id] = self::model('User')->find($id);
    //     }
    //
    //     return $this->users[$id];
    // }
}
