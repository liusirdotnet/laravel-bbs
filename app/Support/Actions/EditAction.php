<?php

namespace App\Support\Actions;

use App\Support\Contracts\Actions\AbstractAction;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return '编辑';
    }

    public function getIcon()
    {
        return 'voyager-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right edit',
        ];
    }

    public function getDefaultRoute()
    {
        return route('admin.' . $this->dataType->slug . '.edit', $this->data->{$this->data->getKeyName()});
    }
}
