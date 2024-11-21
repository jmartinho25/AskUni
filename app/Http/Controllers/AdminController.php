<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->roles->contains('name', 'admin')) {
            abort(403, 'Unauthorized');
        }

        $users = User::all();

        return view('pages.admin.dashboard', compact('users'));
    }
}
