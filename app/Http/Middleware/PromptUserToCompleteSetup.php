<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Providers\RouteServiceProvider;

class PromptUserToCompleteSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        info (json_encode($request->path()));

        if (Auth::user()->setup_step < 4 && $request->path() != RouteServiceProvider::HOME) {
            return redirect(RouteServiceProvider::HOME);
        }


        return $next($request);
    }
}
