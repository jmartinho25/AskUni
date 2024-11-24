<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        
        // Get all users that are not marked as deleted
        $users = User::whereNull('deleted_at')->get();
        
        // Pass users to the view
        return view('pages.admin.dashboard', compact('users'));
    }

}
