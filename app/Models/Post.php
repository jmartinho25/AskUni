<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}

