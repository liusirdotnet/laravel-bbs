<?php

namespace App\Support\Actions;

use App\Support\Contracts\Actions\AbstractAction;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return '查看';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning pull-right view',
        ];
    }

    public function getDefaultRoute()
    {
        return route('admin.' . $this->dataType->slug . '.show', $this->data->{$this->data->getKeyName()});
    }
}
