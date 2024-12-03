<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Answer extends Model
{
    use HasFactory;
    protected $primaryKey = 'posts_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'posts_id',
        'questions_id',
    ];

    /**
     * Get the question that the answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'questions_id', 'posts_id');
    }

    /**
     * Get the post that owns the answer.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'posts_id', 'posts_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($answer) {
            $answer->comments()->each(function ($comment) {
                $comment->delete();
            });
        });
    }
}