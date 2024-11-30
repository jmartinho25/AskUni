<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeNotification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'notifications_id';
    protected $table = 'badges_notifications';

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notifications_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badges_id');
    }
}
