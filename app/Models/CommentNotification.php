<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentNotification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'notifications_id';
    protected $table = 'comments_notifications';

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notifications_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comments_id');
    }
}