<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionNotification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'notifications_id';
    protected $table = 'questions_notifications';

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notifications_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'questions_id');
    }
}
