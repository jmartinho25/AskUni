<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerNotification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'notifications_id';
    protected $table = 'answers_notifications';

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notifications_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answers_id');
    }
}
