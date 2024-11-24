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
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        if (!Auth::user()->roles->contains('name', 'admin')) {
            return redirect()->route('feed')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}