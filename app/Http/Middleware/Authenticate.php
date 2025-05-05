<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (auth()->check() && auth()->user()->status === 0) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'თქვენი ანგარიში დეაქტივირებულია']);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
