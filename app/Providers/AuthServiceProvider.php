<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Role::class => \App\Policies\Policy::class,
        \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
        \App\Models\Menu::class => \App\Policies\Policy::class,
        \App\Models\MenuItem::class => \App\Policies\MenuItemPolicy::class,
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
