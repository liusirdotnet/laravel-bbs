<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    public function updateRoles(User $user, $model)
    {
        $another = $user->id !== $model->id;

        return $another && $user->hasPermission('edit_users');
    }
}
