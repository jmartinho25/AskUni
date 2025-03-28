<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'read_status',
        'date',
        'users_id',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function questionNotification()
    {
        return $this->hasOne(QuestionNotification::class, 'notifications_id');
    }

    public function answerNotification()
    {
        return $this->hasOne(AnswerNotification::class, 'notifications_id');
    }

    public function badgeNotification()
    {
        return $this->hasOne(BadgeNotification::class, 'notifications_id');
    }

    public function commentNotification()
    {
        return $this->hasOne(CommentNotification::class, 'notifications_id');
    }

}