<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\MenuItem;
use App\Policies\Policy;
use App\Policies\UserPolicy;
use Laravel\Horizon\Horizon;
use App\Policies\ReplyPolicy;
use App\Policies\TopicPolicy;
use App\Policies\MenuItemPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class     => UserPolicy::class,
        Role::class     => Policy::class,
        Topic::class    => TopicPolicy::class,
        Reply::class    => ReplyPolicy::class,
        Menu::class     => Policy::class,
        MenuItem::class => MenuItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Horizon::auth(function ($request) {

            // 是否为站长。
            return Auth::user()->hasRole('Founder');
        });
    }
}
