<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'users_likes_posts'; 
    public $timestamps = false;
    protected $fillable = [
        'users_id', 
        'posts_id', 
    ];

    public $incrementing = false; 
    protected $keyType = 'string'; 

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id', 'id');
    }
}