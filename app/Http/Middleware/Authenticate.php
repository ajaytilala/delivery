<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest() && ($guard == "api" ||  $guard == "sanctum" )) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Authentication fail'
                ];
                return Response::json($response);
            }
        } else {
            if (Auth::guard($guard)->guest()) {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
