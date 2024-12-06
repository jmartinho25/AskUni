<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportQuestion extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $fillable = ['content', 'date', 'users_id', 'solved'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function answers()
    {
        return $this->hasMany(SupportAnswer::class, 'support_questions_id');
    }
}