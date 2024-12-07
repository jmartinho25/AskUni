<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['content', 'date', 'posts_id', 'users_id'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'posts_id', 'posts_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'posts_id', 'posts_id');
    }
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function reports()
    {
        return $this->hasMany(ContentReports::class, 'comments_id');
    }

    public function editHistories()
    {
        return $this->hasMany(EditHistory::class, 'comments_id');
    }
}