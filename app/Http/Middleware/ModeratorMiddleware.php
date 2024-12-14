<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if (!$user->roles->contains('name', 'moderator') && 
            !$user->roles->contains('name', 'admin')) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}