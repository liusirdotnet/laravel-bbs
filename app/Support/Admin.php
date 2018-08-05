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
use Illuminate\Support\Facades\Storage;

class Admin
{
    /**
     * @var string
     */
    protected $version = 'v.0.1';

    /**
     * @var array
     */
    protected $alerts = [];

    /**
     * @var array
     */
    protected $actions = [
        DeleteAction::class,
        EditAction::class,
        ViewAction::class,
    ];

    /**
     * @var array
     */
    protected $formFields = [];

    /**
     * @var array
     */
    protected $afterFormFields = [];

    /**
     * @var bool
     */
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

    public function getImage($file, $default = '')
    {
        if (! empty ($file)) {
            return str_replace('\\', '/', Storage::disk(config('admin.storage.disk'))->url($file));
        }

        return $default;
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

    public function afterFormFields($row, $dataType, $dataTypeContent)
    {
        $options = json_decode($row->details);

        $collect = collect($this->afterFormFields)->filter(function ($after) use (
            $row,
            $dataType,
            $dataTypeContent,
            $options
        ) {
            return $after->visible($row, $dataType, $dataTypeContent, $options);
        });

        return $collect;
    }

    public function getVersion()
    {
        return $this->version;
    }
}
