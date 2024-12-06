<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $users = User::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->paginate(10);

        return view('pages.admin.dashboard', compact('users', 'query'));
    }

    public function elevate($id)
    {
        $user = User::findOrFail($id);

        // Verificar se o usuário já é admin ou está eliminado
        if ($user->hasRole('admin') || $user->deleted_at) {
            return redirect()->route('admin.dashboard')->with('error', 'User cannot be elevated to admin.');
        }

        // Adicionar o papel de admin
        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);

        return redirect()->route('admin.dashboard')->with('success', 'User has been elevated to admin.');
    }
}