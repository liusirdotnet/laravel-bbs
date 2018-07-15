<?php

namespace App\Widgets\Admin;

use App\Support\Contracts\WidgetInterface;
use App\Support\Facades\Admin;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;

class TopicWidget extends AbstractWidget implements WidgetInterface
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('widgets.admin.panel_widget', array_merge($this->config, [
            'icon'   => 'voyager-news',
            'title'  => '',
            'text'   => '',
            'button' => [
                'text' => __('查看所有话题'),
                'link' => '/',
            ],
            'image'  => asset('backend/images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('access', Admin::getModel('Topic'));
    }
}
