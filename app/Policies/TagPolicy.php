<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagPolicy
{

    public function follow(User $user, Tag $tag)
    {
        return $user !== null && $tag !== null;
    }

    public function manage(User $user)
    {
        return $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'moderator');
    }
    
}