<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function editUser(User $authUser, User $user)
    {
        return $authUser->id === $user->id;
    }

    public function admin(User $user)
    {
        return $user->roles->contains('name', 'admin');
    }
}
