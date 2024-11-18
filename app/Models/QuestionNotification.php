<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsNotification extends Model
{
    use HasFactory;

    // No automatic timestamps.
    public $timestamps = false;

    /**
     * Get the notification associated with the question notification.
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    /**
     * Get the question associated with the question notification.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
