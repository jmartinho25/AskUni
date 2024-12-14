<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Answer $answer)
    {
        return $user->id === $answer->post->users_id;
    }

    public function delete(User $user, Answer $answer)
    {
        return $user->id === $answer->post->users_id || $user->hasRole('admin') || $user->hasRole('moderator');
    }
}