<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    use HasFactory;

    protected $table = 'users_dislikes_posts'; 
    public $timestamps = false;
    protected $fillable = [
        'users_id', 
        'posts_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }
}