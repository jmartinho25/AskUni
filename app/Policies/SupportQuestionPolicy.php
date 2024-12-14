<?php

namespace App\Policies;

use App\Models\SupportQuestion;
use App\Models\User;

class SupportQuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'moderator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->id === $supportQuestion->users_id ||
               $user->roles->contains('name', 'admin') ||
               $user->roles->contains('name', 'moderator');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Qualquer usuário autenticado pode criar uma pergunta de suporte
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->id === $supportQuestion->users_id ||
               $user->roles->contains('name', 'admin') ||
               $user->roles->contains('name', 'moderator');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->id === $supportQuestion->users_id ||
               $user->roles->contains('name', 'admin') ||
               $user->roles->contains('name', 'moderator');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->roles->contains('name', 'admin') ||
               $user->roles->contains('name', 'moderator');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->roles->contains('name', 'admin') ||
               $user->roles->contains('name', 'moderator');
    }
    
    public function solve(User $user, SupportQuestion $supportQuestion)
    {
        return $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'moderator');
    }
}