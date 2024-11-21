<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response('User Not Authenticated', 401);
        }

        if (Auth::user()->roles->contains('name', 'admin')) {
            return $next($request);
        }

        return response('Unauthorized', 403);
    }

}
