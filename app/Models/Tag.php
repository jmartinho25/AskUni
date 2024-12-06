<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description'];

    public $timestamps = false;

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'posts_tags', 'tags_id', 'posts_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_follow_tags', 'tags_id', 'users_id');
    }
}