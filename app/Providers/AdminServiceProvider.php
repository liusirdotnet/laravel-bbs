<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Policies\MenuItemPolicy;
use App\Policies\Policy;
use App\Support\Facades\Admin;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        MenuItem::class => MenuItemPolicy::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerGates();
        $this->registerViewComposers();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Admin', Admin::class);

        $this->app->singleton('admin', function () {
            return new \App\Support\Admin();
        });

        $this->registerAlertComponents();
        $this->registerFormFields();
    }

    public function registerGates()
    {
        try {
            if (Schema::hasTable('data_types')) {
                $dataType = Admin::getModel('DataType');
                $dataTypes = $dataType->select('policy_name', 'model_name')->get();

                foreach ($dataTypes as $dataType) {
                    $class = Policy::class;
                    if (isset($dataType->policy_name)
                        && null !== $dataType->policy_name
                        && class_exists($dataType->policy_name)
                    ) {
                        $class = $dataType->policy_name;
                    }
                    $this->policies[$dataType->model_name] = $class;
                }

                $this->registerPolicies();
            }
        } catch (\PDOException $e) {
            Log::error('No Database connection yet in AdminServiceProvider registerGates()');
        }
    }

    public function registerViewComposers()
    {
        View::composer('*', function (\Illuminate\View\View $view) {
            $view->with('alerts', Admin::getAlerts());
        });
    }

    public function registerAlertComponents()
    {
        $components = ['button'];

        foreach ($components as $component) {
            $class = 'App\\Support\\Components\\' . ucfirst(camel_case($component)) . 'Component';

            $this->app->bind("admin.alert.components.{$component}", $class);
        }
    }

    public function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'color',
            'date',
            'file',
            'image',
            // 'multiple_image',
            'number',
            'password',
            // 'radio_btn',
            // 'rich_textbox',
            // 'code_editor',
            // 'markdown_editor',
            // 'dropdown_select',
            // 'multiple_select',
            'text',
            'textarea',
            // 'time',
            'timestamp',
            'hidden',
            // 'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = studly_case("{$formField}_field");

            Admin::addFormField("App\\Support\\Forms\\Fields\\$class");
        }
    }
}
