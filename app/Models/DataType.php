<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Facades\Admin;

class DataType extends Model
{
    /**
     * @var string
     */
    protected $table = 'data_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions',
        'server_side',
        'order_column',
        'order_display_column',
    ];

    public function rows()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))->orderBy('order');
    }

    public function accessRows()
    {
        return $this->rows()->where('access', 1);
    }

    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    public function lastRow()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))
            ->orderBy('order', 'DESC')
            ->first();
    }
}
