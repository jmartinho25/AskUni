<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentReports extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['report_reason', 'date', 'solved', 'comments_id', 'posts_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comments_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }
}