<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealForUnblock extends Model
{
    use HasFactory;

    protected $table = 'appeal_for_unblocks';

    protected $fillable = [
        'content',
        'users_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}