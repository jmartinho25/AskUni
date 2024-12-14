<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function elevateToModerator($id)
    {
        $user = User::findOrFail($id);
        $moderatorRole = Role::where('name', 'moderator')->first();

        if (!$moderatorRole) {
            return redirect()->back()->with('error', 'Moderator role not found.');
        }

        if ($user->roles->contains($moderatorRole)) {
            return redirect()->back()->with('error', 'User is already a moderator.');
        }

        $user->roles()->attach($moderatorRole);

        return redirect()->back()->with('success', 'User has been elevated to moderator successfully.');
    }
}