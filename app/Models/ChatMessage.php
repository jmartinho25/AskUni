<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $fillable = ['sender_id', 'message', 'created_at'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}