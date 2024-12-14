<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModeratorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (!auth()->user()->roles->contains('name', 'moderator') && 
            !auth()->user()->roles->contains('name', 'admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}