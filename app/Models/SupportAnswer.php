<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'date', 'users_id', 'support_questions_id'];

    public $timestamps  = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function question()
    {
        return $this->belongsTo(SupportQuestion::class, 'support_questions_id');
    }
}