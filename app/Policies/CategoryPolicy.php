<?php

namespace App\Policies\Admin;

use App\Models\User;
use App\Policies\Policy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
