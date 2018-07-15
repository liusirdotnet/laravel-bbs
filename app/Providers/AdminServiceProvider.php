<?php

namespace App\Providers;

use App\Support\Facades\Admin;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
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
            return new Admin();
        });

        $this->registerAlertComponents();
        $this->registerFormFields();
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
            // 'multiple_images',
            // 'number',
            // 'password',
            // 'radio_btn',
            // 'rich_text_box',
            // 'code_editor',
            // 'markdown_editor',
            // 'select_dropdown',
            // 'select_multiple',
            'text',
            // 'text_area',
            // 'time',
            'timestamp',
            // 'hidden',
            // 'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = studly_case("{$formField}_field");

            Admin::addFormField("App\\Support\\Forms\\Fields\\$class");
        }
    }
}
