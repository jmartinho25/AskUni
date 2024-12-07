<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    // Don't add timestamps for created_at and updated_at in the database.
    public $timestamps  = false;

    /**
     * Fillable fields for the post model.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'date',
        'users_id',     // Foreign key for the user
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function question(): HasOne
    {
        return $this->hasOne(Question::class, 'posts_id');
    }

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class, 'posts_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'posts_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'posts_id', 'tags_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'posts_id', 'id'); 
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(Dislike::class, 'posts_id', 'id'); 
    }

    public function likesCount(): int
    {
        return $this->likes()->count(); 
    }

    public function dislikesCount(): int
    {
        return $this->dislikes()->count(); 
    }
    
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('users_id', $user->id)->exists();
    }

    public function isDislikedBy(User $user): bool
    {
        return $this->dislikes()->where('users_id', $user->id)->exists();
    }

}

