<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Question;
use App\Models\Post;
use App\Policies\QuestionPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\AnswerPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\SupportQuestion;
use App\Policies\SupportQuestionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Question::class => QuestionPolicy::class,
        Answer::class => AnswerPolicy::class,
        Comment::class => CommentPolicy::class,
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        SupportQuestion::class => SupportQuestionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
