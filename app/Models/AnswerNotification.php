<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswersNotification extends Model
{
    use HasFactory;

    // No automatic timestamps.
    public $timestamps = false;

    /**
     * Get the notification associated with the answer notification.
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    /**
     * Get the answer associated with the answer notification.
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
