<?php

namespace App\Models\Traits;

use App\Support\Facades\Admin;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait UserTrait
{
    public function role()
    {
        return $this->belongsTo(Admin::getModelClass('Role'));
    }

    public function roles()
    {
        return $this->belongsToMany(Admin::getModelClass('Role'), 'user_roles');
    }

    public function getAllRoles()
    {
        $this->loadRolesRelations();

        return collect([$this->role])->merge($this->roles);
    }

    public function hasRole($name)
    {
        $roles = $this->getAllRoles()->pluck('name')->toArray();

        foreach ((array) $name as $role) {
            if (\in_array(strtolower($role), $roles, true)) {
                return true;
            }
        }

        return false;
    }

    public function setRole($name)
    {
        $role = Admin::getModel('Role')->where('name', '=', $name)->first();

        if ($role) {
            $this->role()->associate($role);
            $this->save();
        }

        return $this;
    }

    public function hasPermission($name)
    {
        $this->loadPermissionsRelations();

        $permissions = $this->getAllRoles()
            ->pluck('permissions')
            ->flatten()
            ->pluck('action')
            ->unique()
            ->toArray();

        return \in_array(strtolower($name), $permissions, true);
    }

    public function hasPermissionOrFail($name)
    {
        if (! $this->hasPermission($name)) {
            throw new UnauthorizedHttpException(null);
        }
    }

    public function hasPermissionOrAbort($name, $statusCode = 403)
    {
        if (! $this->hasPermission($name)) {
            return abort($statusCode);
        }

        return true;
    }

    private function loadPermissionsRelations()
    {
        $this->loadRolesRelations();

        if (! $this->role->relationLoaded('permissions')) {
            $this->role->relationLoaded('permissions');
            $this->load('roles.permissions');
        }
    }

    private function loadRolesRelations()
    {
        if (! $this->relationLoaded('role')) {
            $this->load('role');
        }

        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }
    }
}
