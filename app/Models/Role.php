<?php

namespace App\Models;

use App\Models\Traits\HasRelationshipTrait;
use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasRelationshipTrait;

    public function users()
    {
        $user = Admin::getModelClass('User');

        return $this->belongsToMany($user, 'user_roles')
            ->select(app($user)->getTable() . '*')
            ->union($this->hasMany($user))
            ->getQuery();
    }

    public function permissions()
    {
        $permission = Admin::getModelClass('Permission');

        return $this->belongsToMany($permission, 'permission_roles');
    }
}
