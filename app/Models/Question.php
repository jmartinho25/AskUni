<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'posts_id';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'posts_id', // Relationship with the Post
        'title',    // Question title
    ];

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the post that owns the question.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function user(): BelongsTo
    {
        return $this->post->user();
    }
}

