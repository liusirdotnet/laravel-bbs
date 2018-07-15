<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (! Auth::guest()) {
            /** @var \App\Models\User $user */
            $user = \auth()->user();

            return $user->hasPermission('access_admin') ? $next($request) : redirect('/');
        }

        return redirect()->guest(route('login'));
    }
}
