<?php

namespace App\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'display_name' => $role->display_name,
        ];
    }
}
