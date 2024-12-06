<?php

namespace App\Policies;

use App\Models\SupportQuestion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupportQuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportQuestion $supportQuestion): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupportQuestion $supportQuestion): bool
    {
        return $user->id === $supportQuestion->users_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupportQuestion $supportQuestion): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupportQuestion $supportQuestion): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupportQuestion $supportQuestion): bool
    {
        //
    }
}
