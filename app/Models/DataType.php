<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Facades\Admin;

class DataType extends Model
{
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rows()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessRows()
    {
        return $this->rows()->where('access', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     */
    public function lastRow()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))
            ->orderBy('order', 'DESC')
            ->first();
    }
}
