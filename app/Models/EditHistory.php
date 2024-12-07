<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'edit_histories';

    protected $fillable = [
        'previous_content',
        'new_content',
        'date',
        'posts_id',
        'comments_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comments_id');
    }
}