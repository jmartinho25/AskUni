<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'date',
        'users_id',     //fillable foreign key?
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
}
