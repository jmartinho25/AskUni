<?php

namespace App\Models;

// Necessary traits and base classes
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // Disable default timestamps for this model.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'description',
        'photo',
        'is_blocked',
        'score',
    ];

    /**
     * The attributes that should be hidden when serialized.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
        'score' => 'integer',
    ];

    /**
     * Get the posts associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'users_id');
    }

    /**
     * Get the questions associated with the user's posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(Question::class, Post::class, 'users_id', 'posts_id');
    }

    /**
     * Get the answers associated with the user's posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(): HasMany
    {
        return $this->posts()->whereHas('answer')->with('answer');
    }

    /**
     * Get the comments associated with the user's posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'users_id');
    }

    /**
     * Get the notifications associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'users_id');
    }

    /**
     * Get the roles assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'users_id', 'roles_id');
    }
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
    public function assignRole($role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        $this->roles()->attach($role);
    }
    public function followedQuestions()
    {
        return $this->belongsToMany(Question::class, 'users_follow_questions', 'users_id', 'questions_id');
    }
    /**
     * Get the tags followed by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'users_follow_tags', 'users_id', 'tags_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'earned_badges', 'users_id', 'badges_id')
                    ->withPivot('date');
    }
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'users_likes_posts', 'users_id', 'posts_id');
    }

    public function dislikes()
    {
        return $this->belongsToMany(Post::class, 'users_dislikes_posts', 'users_id', 'posts_id');
    }

    public function hasLiked(Post $post): bool
    {
        return $this->likes()->where('posts_id', $post->id)->exists();
    }

    public function like(Post $post): void
    {
        if (!$this->hasLiked($post)) {
            $this->likes()->attach($post->id);
        }
    }

    public function unlike(Post $post): void
    {
        if ($this->hasLiked($post)) {
            $this->likes()->detach($post->id);
        }
    }

    public function hasDisliked(Post $post): bool
    {
        return $this->dislikes()->where('posts_id', $post->id)->exists();
    }

    public function dislike(Post $post): void
    {
        if (!$this->hasDisliked($post)) {
            $this->dislikes()->attach($post->id);
        }
    }

    public function undislike(Post $post): void
    {
        if ($this->hasDisliked($post)) {
            $this->dislikes()->detach($post->id);
        }
    }
}
